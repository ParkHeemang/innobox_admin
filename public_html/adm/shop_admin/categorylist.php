<?php
$sub_menu = '400200';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '분류관리';
include_once ('../admin.head.php');

$where = " where ";
$sql_search = "";
if ($stx != "") {
    if ($sfl != "") {
        $sql_search .= " $where $sfl like '%$stx%' ";
        $where = " and ";
    }
    if ($save_stx && ($save_stx != $stx))
        $page = 1;
}

$sql_common = " from {$g5['g5_shop_category_table']} ";
if ($is_admin != 'super')
    $sql_common .= " $where ca_mb_id = '{$member['mb_id']}' ";
$sql_common .= $sql_search;


// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

if (!$sst)
{
    $sst  = "ca_id";
    $sod = "asc";
}
$sql_order = "order by $sst $sod";
$sql_order = "order by ca_order, ca_id";

// 출력할 레코드를 얻음
$sql  = " select *
             $sql_common
             $sql_order
             limit $from_record, $rows ";
$result = sql_query($sql);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';

add_stylesheet('<link rel="stylesheet" href="'.G5_PHP_URL. 'bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 5);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL2.'/bootstrap.custom.css?ver='.G5_CSS_VER.'"></script>',8);


//JS DataTables
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);

?>




<script>
$(function() {
    $('#cate_ord').on('click', function() {
        var $this = $(this),
            _href = $this.attr('href');

        if (_href) {
            window.open(_href, 'cate_ord', 'width=600,height=800');
        }

        return false;
    });

    $("select.skin_dir").on("change", function() {
        var type = "";
        var dir = $(this).val();
        if(!dir)
            return false;

        var id = $(this).attr("id");
        var $sel = $(this).siblings("select");
        var sval = $sel.find("option:selected").val();

        if(id.search("mobile") > -1)
            type = "mobile";

        $sel.load(
            "./ajax.skinfile.php",
            { dir : dir, type : type, sval: sval }
        );
    });
});
</script>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		분류관리
		<small>category list</small>
		</h1>
		<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">회원관리</a></li>
		<li class="active">회원정보리스트</li>
		</ol>
	</section>
	<!-- Main content -->	
	<section class="content">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn" style="z-index: 1000000000000000000000000000000000">
							<div class="text-right">							
								<input type="submit" value="일괄수정" class="btn_02 btn">
								<?php if ($is_admin == 'super') {?>
								<a href="./categoryform.php" id="cate_add" class="btn btn_01 btn-danger">분류 추가</a>
								<?php } ?>					
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body">						
						<form name="flist" class="local_sch01 local_sch">
						<input type="hidden" name="page" value="<?php echo $page; ?>">
						<input type="hidden" name="save_stx" value="<?php echo $stx; ?>">

						<label for="sfl" class="sound_only">검색대상</label>
						<select name="sfl" id="sfl" class="form-control">
							<option value="ca_name"<?php echo get_selected($_GET['sfl'], "ca_name", true); ?>>분류명</option>
							<option value="ca_id"<?php echo get_selected($_GET['sfl'], "ca_id", true); ?>>분류코드</option>
							<option value="ca_mb_id"<?php echo get_selected($_GET['sfl'], "ca_mb_id", true); ?>>회원아이디</option>
						</select>

						<!-- <label for="stx" class="sound_only">검색어<strong class="sound_only"> 필수</strong></label> -->
						<input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" required class="required frm_input form-control">
						<input type="submit" value="검색" class="btn_submit btn btn-default">
						<a href="./categoryroll.php" id="cate_ord" class="btn btn-dark float-right bg-black-active">분류 순서 변경</a>
						</form>											

						<form name="fcategorylist" method="post" action="./categorylistupdate.php" autocomplete="off">
						<input type="hidden" name="sst" value="<?php echo $sst; ?>">
						<input type="hidden" name="sod" value="<?php echo $sod; ?>">
						<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
						<input type="hidden" name="stx" value="<?php echo $stx; ?>">
						<input type="hidden" name="page" value="<?php echo $page; ?>">

						<div id="sct" class="tbl_head01 tbl_wrap">
							<table id="example1" class="table table-bordered table-striped dataTable member_list" width="100%">
								<!-- <caption><?php echo $g5['title']; ?> 목록</caption> -->
								<colgroup>
									<col width="80">
									<col>
									<col width="50">
									<col width="50">
									<col width="140">
									<col width="170">
									<col width="140">
									<col width="170">
									<col width="150">
								</colgroup>
								<thead>
								<tr>
									<th scope="col">분류코드</th>
									<th scope="col" id="sct_cate">분류명</th>
									<th scope="col" id="sct_amount">등록된<br>상품수</th>
									<!-- <th scope="col" id="sct_order">출력<br>순서</th> -->

									<th scope="col" id="sct_sell">판매<br>가능</th>
									<!-- <th scope="col" id="sct_hpcert">본인<br>인증</th> -->
									<!-- <th scope="col" id="sct_adultcert">성인<br>인증</th> -->

									<th scope="col">출력이미지<br>가로×세로(px)</th>
									<th scope="col">PC<br>상품출력수</th>
									<th scope="col">모바일이미지<br>가로×세로(px)</th>
									<th scope="col">Mobile<br>상품출력수</th>

									<th scope="col">관리</th>
								</tr>
								</thead>
								<tbody>
								<?php
								for ($i=0; $row=sql_fetch_array($result); $i++)
								{
									$level = strlen($row['ca_id']) / 2 - 1;
									$p_ca_name = '';

									if ($level > 0) {
										$class = 'class="name_label"'; // 2단 이상 분류의 label 에 스타일 부여 - 지운아빠 2013-04-02
										// 상위단계의 분류명
										$p_ca_id = substr($row['ca_id'], 0, $level*2);
										$sql = " select ca_name from {$g5['g5_shop_category_table']} where ca_id = '$p_ca_id' ";
										$temp = sql_fetch($sql);
										$p_ca_name = $temp['ca_name'].'의하위';
									} else {
										$class = 'class="name_empty"';
									}

									$s_level = '<div><label for="ca_name_'.$i.'" '.$class.'><span class="sound_only">'.$p_ca_name.''.($level+1).'단 분류</span></label></div>';
									$s_level_input_size = 25 - $level *2; // 하위 분류일 수록 입력칸 넓이 작아짐 - 지운아빠 2013-04-02

									if ($level+2 < 6) $s_add = '<a href="./categoryform.php?ca_id='.$row['ca_id'].'&amp;'.$qstr.'" class="btn btn-primary">추가</a> '; // 분류는 5단계까지만 가능
									else $s_add = '';
									$s_upd = '<a href="./categoryform.php?w=u&amp;ca_id='.$row['ca_id'].'&amp;'.$qstr.'" class="btn btn-info">수정</a> ';

									if ($is_admin == 'super')
										$s_del = '<a href="./categoryformupdate.php?w=d&amp;ca_id='.$row['ca_id'].'&amp;'.$qstr.'" onclick="return delete_confirm(this);" class="btn btn-danger">삭제</a> ';

									// 해당 분류에 속한 상품의 수
									$sql1 = " select COUNT(*) as cnt from {$g5['g5_shop_item_table']}
												  where ca_id like '{$row['ca_id']}%'
												  or ca_id2 like '{$row['ca_id']}%'
												  or ca_id3 like '{$row['ca_id']}%' ";
									$row1 = sql_fetch($sql1);

									// 스킨 Path
									if(!$row['ca_skin_dir'])
										$g5_shop_skin_path = G5_SHOP_SKIN_PATH;
									else {
										if(preg_match('#^theme/(.+)$#', $row['ca_skin_dir'], $match))
											$g5_shop_skin_path = G5_THEME_PATH.'/'.G5_SKIN_DIR.'/shop/'.$match[1];
										else
											$g5_shop_skin_path  = G5_PATH.'/'.G5_SKIN_DIR.'/shop/'.$row['ca_skin_dir'];
									}

									if(!$row['ca_mobile_skin_dir'])
										$g5_mshop_skin_path = G5_MSHOP_SKIN_PATH;
									else {
										if(preg_match('#^theme/(.+)$#', $row['ca_mobile_skin_dir'], $match))
											$g5_mshop_skin_path = G5_THEME_MOBILE_PATH.'/'.G5_SKIN_DIR.'/shop/'.$match[1];
										else
											$g5_mshop_skin_path = G5_MOBILE_PATH.'/'.G5_SKIN_DIR.'/shop/'.$row['ca_mobile_skin_dir'];
									}

									//$bg = 'bg'.($i%2);
									$clen = strlen($row['ca_id']) / 2;
									switch ($clen) {
										case 1 : $bgclr = '#f2f2f2'; break;
										default : $bgclr = '#ffffff';
									}
								?>
								<tr class="<?php echo $bg; ?>" style="background-color:<?php echo $bgclr; ?>;">
									<td>
										<input type="hidden" name="ca_id[<?php echo $i; ?>]" value="<?php echo $row['ca_id']; ?>">
										<!-- <a href="<?php echo G5_SHOP_URL; ?>/list.php?ca_id=<?php echo $row['ca_id']; ?>"><?php echo $row['ca_id']; ?></a> -->
										<input type="text" value="<?php echo $row['ca_id']; ?>" readonly class="frm_input form-control full_input">
									</td>
									<td headers="sct_cate" class="sct_name sct_name<?php echo $level; ?> w-auto"><?php echo $s_level; ?> <input type="text" name="ca_name[<?php echo $i; ?>]" value="<?php echo get_text($row['ca_name']); ?>" id="ca_name_<?php echo $i; ?>" required class="frm_input form-control full_input required accented classfying_name"></td>
									<td headers="sct_amount" class="td_amount"><a href="./itemlist.php?sca=<?php echo $row['ca_id']; ?>"><?php echo $row1['cnt']; ?></a></td>

									<!-- 
									<td headers="sct_order" class="td_order">
										<input type="text" name="ca_order[<?php echo $i; ?>]" value="<?php echo $row['ca_order']; ?>" id="ca_order<?php echo $i; ?>" class="required frm_input form-control" size="3">
										<label for="ca_order<?php echo $i; ?>" class="sound_only">출력순서</label>
									</td> 
									-->
									<td headers="sct_sell">
										<input type="checkbox" name="ca_use[<?php echo $i; ?>]" value="1" id="ca_use<?php echo $i; ?>" <?php echo ($row['ca_use'] ? "checked" : ""); ?>>
										<!-- <label for="ca_use<?php echo $i; ?>" class="sound_only">판매</label> -->
									</td>
									<!--  
									<td headers="sct_hpcert">
										<input type="checkbox" name="ca_cert_use[<?php echo $i; ?>]" value="1" id="ca_cert_use_yes<?php echo $i; ?>" <?php if($row['ca_cert_use']) echo 'checked="checked"'; ?>>
										<label for="ca_cert_use_yes<?php echo $i; ?>" class="sound_only">사용</label>
									</td>
									-->
									<!-- 
									<td headers="sct_adultcert">
										<input type="checkbox" name="ca_adult_use[<?php echo $i; ?>]" value="1" id="ca_adult_use_yes<?php echo $i; ?>" <?php if($row['ca_adult_use']) echo 'checked="checked"'; ?>>
										<label for="ca_adult_use_yes<?php echo $i; ?>" class="sound_only">사용</label>
									</td>
									-->

									<td>
										<!-- <label for="ca_img_width<?php echo $i; ?>" class="sound_only">출력이미지 폭</label> -->
										<input type="text" name="ca_img_width[<?php echo $i; ?>]" value="<?php echo get_text($row['ca_img_width']); ?>" id="ca_img_width<?php echo $i; ?>" required class="required frm_input form-control w-auto text-center" size="3"> <span class="sound_only">픽셀</span>
										×
										<!-- <label for="ca_img_height<?php echo $i; ?>" class="sound_only">출력이미지 높이</label> -->
										<input type="text" name="ca_img_height[<?php echo $i; ?>]" value="<?php echo $row['ca_img_height']; ?>" id="ca_img_height<?php echo $i; ?>" required class="required frm_input form-control w-auto text-center" size="3"> <span class="sound_only">픽셀</span>
									</td>
									<td>
										<!-- <label for="ca_lineimg_num<?php echo $i; ?>" class="sound_only">1줄당 이미지 수</label> -->
										<input type="text" name="ca_list_mod[<?php echo $i; ?>]" size="2" value="<?php echo $row['ca_list_mod']; ?>" id="ca_lineimg_num<?php echo $i; ?>" required class="required frm_input form-control w-auto text-center"> <span>개</span>
										×
										<!-- <label for="ca_imgline_num<?php echo $i; ?>" class="sound_only">이미지 줄 수</label> -->
										<input type="text" name="ca_list_row[<?php echo $i; ?>]" value='<?php echo $row['ca_list_row']; ?>' id="ca_imgline_num<?php echo $i; ?>" required class="required frm_input form-control w-auto text-center" size="2"> <span>줄</span>
									</td>

									<td>
										<!-- <label for="ca_mobile_img_width<?php echo $i; ?>" class="sound_only">모바일 출력이미지 폭</label> -->
										<input type="text" name="ca_mobile_img_width[<?php echo $i; ?>]" value="<?php echo get_text($row['ca_mobile_img_width']); ?>" id="ca_mobile_img_width<?php echo $i; ?>" required class="required frm_input form-control w-auto text-center" size="3"> <span class="sound_only">픽셀</span>
										×
										<!-- <label for="ca_mobile_img_height<?php echo $i; ?>" class="sound_only">모바일 출력이미지 높이</label> -->
										<input type="text" name="ca_mobile_img_height[<?php echo $i; ?>]" value="<?php echo $row['ca_mobile_img_height']; ?>" id="ca_mobile_img_height<?php echo $i; ?>" required class="required frm_input form-control w-auto text-center" size="3"> <span class="sound_only">픽셀</span>
									</td>
									<td>
										<!-- <label for="ca_mobileimg_num<?php echo $i; ?>" class="sound_only">모바일 1줄당 이미지 수</label> -->
										<input type="text" name="ca_mobile_list_mod[<?php echo $i; ?>]" size="2" value="<?php echo $row['ca_mobile_list_mod']; ?>" id="ca_mobileimg_num<?php echo $i; ?>" required class="required frm_input form-control w-auto text-center"> <span>개</span>
										×
										<!-- <label for="ca_mobileimg_row<?php echo $i; ?>" class="sound_only">모바일 이미지 줄 수</label> -->
										<input type="text" name="ca_mobile_list_row[<?php echo $i; ?>]" value='<?php echo $row['ca_mobile_list_row']; ?>' id="ca_mobileimg_row<?php echo $i; ?>" required class="required frm_input form-control w-auto text-center" size="2"> <span>줄</span>
									</td>

									<td class="td_mng">
										<?php echo $s_add; ?>
										<?php echo $s_vie; ?>
										<?php echo $s_upd; ?>
										<?php echo $s_del; ?>
									</td>
								</tr>
								<?php }
								if ($i == 0) echo "<tr><td colspan=\"13\" class=\"empty_table\">자료가 한 건도 없습니다.</td></tr>\n";
								?>
								</tbody>
							</table>
						</div>
						</form>					
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->




<script>


$(document).scroll(function(e){
    var scrollTop = $(document).scrollTop();
    if(scrollTop > 0){
        console.log(scrollTop);
        $('.navbar').removeClass('navbar-static-top').addClass('navbar-fixed-top');
    } else {
        $('.navbar').removeClass('navbar-fixed-top').addClass('navbar-static-top');
    }
});



$(function () {

// Korean
var lang_kor = {
	"decimal" : "",
	"emptyTable" : "데이터가 없습니다.",
	"info" : "_START_ - _END_ (총 _TOTAL_ 명)",
	"infoEmpty" : "0명",
	"infoFiltered" : "(전체 _MAX_ 명 중 검색결과)",
	"infoPostFix" : "",
	"thousands" : ",",
	"lengthMenu" : "_MENU_ 개",
	"loadingRecords" : "로딩중...",
	"processing" : "처리중...",
	"search" : "검색 : ",
	"zeroRecords" : "검색된 데이터가 없습니다.",
	"paginate" : {
		"first" : "첫 페이지",
		"last" : "마지막 페이지",
		"next" : "다음",
		"previous" : "이전"
	},
	"aria" : {
		"sortAscending" : " :  오름차순 정렬",
		"sortDescending" : " :  내림차순 정렬"
	}
};


var table = $('#example1').DataTable({
  'scrollX': true,	 
  'paging'      : true,
  'lengthChange': false,
  'searching'   : false,
  'ordering'    : false,
  'info'        : false,
  'language' : lang_kor,
  'autoWidth'   : true  
  
 });

})


$(function() {
    $('#cate_ord').on('click', function() {
        var $this = $(this),
            _href = $this.attr('href');

        if (_href) {
            window.open(_href, 'cate_ord', 'width=600,height=800');
        }

        return false;
    });

    $("select.skin_dir").on("change", function() {
        var type = "";
        var dir = $(this).val();
        if(!dir)
            return false;

        var id = $(this).attr("id");
        var $sel = $(this).siblings("select");
        var sval = $sel.find("option:selected").val();

        if(id.search("mobile") > -1)
            type = "mobile";

        $sel.load(
            "./ajax.skinfile.php",
            { dir : dir, type : type, sval: sval }
        );
    });
});
</script>

<?php
include_once ('../footer.php');
?>
