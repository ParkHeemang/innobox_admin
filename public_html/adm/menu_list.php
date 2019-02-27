<?php
$sub_menu = "100290";
include_once('./_common.php');

if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.');

// 메뉴테이블 생성
if( !isset($g5['menu_table']) ){
    die('<meta charset="utf-8">dbconfig.php 파일에 <strong>$g5[\'menu_table\'] = G5_TABLE_PREFIX.\'menu\';</strong> 를 추가해 주세요.');
}

if(!sql_query(" DESCRIBE {$g5['menu_table']} ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['menu_table']}` (
                  `me_id` int(11) NOT NULL AUTO_INCREMENT,
                  `me_code` varchar(255) NOT NULL DEFAULT '',
                  `me_name` varchar(255) NOT NULL DEFAULT '',
                  `me_link` varchar(255) NOT NULL DEFAULT '',
                  `me_target` varchar(255) NOT NULL DEFAULT '0',
                  `me_order` int(11) NOT NULL DEFAULT '0',
                  `me_use` tinyint(4) NOT NULL DEFAULT '0',
                  `me_mobile_use` tinyint(4) NOT NULL DEFAULT '0',
                  PRIMARY KEY (`me_id`),
                  KEY `me_code` (`me_code`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ", true);
}

$sql = " select * from {$g5['menu_table']} order by me_order, me_code ";
$result = sql_query($sql);

$g5['title'] = "메뉴설정";
include_once('./admin.head.php');

$colspan = 8;


//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL. '/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 4);

//JS DataTables
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		메뉴설정
		<small>menu list</small>
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
				<form name="fmenulist" id="fmenulist" method="post" action="./menu_list_update.php" onsubmit="return fmenulist_submit(this);">
				<div class="box">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn">
							<div class="text-right">							
								<button type="button" onclick="return add_menu();" class="btn btn_02 btn-primary">메뉴추가<span class="sound_only"> 새창</span></button>
								<input type="submit" name="act_button" value="확인" class="btn_submit btn btn-danger ">
							</div>
						</nav>
					</div>
					<!-- /.box-header -->
					<div class="box-body menu_list">								
						<div class="alert alert-dismissible alert-default">
							<strong>주의!</strong> 메뉴설정 작업 후 반드시 <strong>확인</strong>을 누르셔야 저장됩니다.
						</div>						
						<input type="hidden" name="token" value="">
						<div id="menulist">
							<table id="example1" class="table table-bordered table-striped dataTable level_list" width="100%">
								<caption><?php echo $g5['title']; ?> 목록</caption>
								<thead>
								<tr>
									<th scope="col">메뉴</th>
									<th scope="col">링크</th>
									<th scope="col">새창</th>
									<th scope="col">순서</th>
									<th scope="col">PC사용</th>
									<th scope="col">모바일사용</th>
									<th scope="col">관리</th>
								</tr>
								</thead>
								<tbody>
								<?php
								for ($i=0; $row=sql_fetch_array($result); $i++)
								{
									$bg = 'bg'.($i%2);
									$sub_menu_class = '';
									if(strlen($row['me_code']) == 4) {
										$sub_menu_class = ' sub_menu_class';
										$sub_menu_info = '<span class="sound_only">'.$row['me_name'].'의 서브</span>';
										$sub_menu_ico = '<span class="sub_menu_ico"></span>';
									}

									$search  = array('"', "'");
									$replace = array('&#034;', '&#039;');
									$me_name = str_replace($search, $replace, $row['me_name']);
								?>
								<tr class="<?php echo $bg; ?> menu_list menu_group_<?php echo substr($row['me_code'], 0, 2); ?>">
									<td class="td_category<?php echo $sub_menu_class; ?>">
										<input type="hidden" name="code[]" value="<?php echo substr($row['me_code'], 0, 2) ?>">
										<label for="me_name_<?php echo $i; ?>" class="sound_only"><?php echo $sub_menu_info; ?> 메뉴<strong class="sound_only"> 필수</strong></label>
										<input type="text" name="me_name[]" value="<?php echo $me_name; ?>" id="me_name_<?php echo $i; ?>" required class="required tbl_input full_input form-control">
									</td>
									<td>
										<label for="me_link_<?php echo $i; ?>" class="sound_only">링크<strong class="sound_only"> 필수</strong></label>
										<input type="text" name="me_link[]" value="<?php echo $row['me_link'] ?>" id="me_link_<?php echo $i; ?>" required class="required tbl_input full_input form-control">
									</td>
									<td class="td_mng">
										<label for="me_target_<?php echo $i; ?>" class="sound_only">새창</label>
										<select name="me_target[]" id="me_target_<?php echo $i; ?>">
											<option value="self"<?php echo get_selected($row['me_target'], 'self', true); ?>>사용안함</option>
											<option value="blank"<?php echo get_selected($row['me_target'], 'blank', true); ?>>사용함</option>
										</select>
									</td>
									<td class="td_num">
										<label for="me_order_<?php echo $i; ?>" class="sound_only">순서</label>
										<input type="text" name="me_order[]" value="<?php echo $row['me_order'] ?>" id="me_order_<?php echo $i; ?>" class="tbl_input form-control" size="5">
									</td>
									<td class="td_mng">
										<label for="me_use_<?php echo $i; ?>" class="sound_only">PC사용</label>
										<select name="me_use[]" id="me_use_<?php echo $i; ?>">
											<option value="1"<?php echo get_selected($row['me_use'], '1', true); ?>>사용함</option>
											<option value="0"<?php echo get_selected($row['me_use'], '0', true); ?>>사용안함</option>
										</select>
									</td>
									<td class="td_mng">
										<label for="me_mobile_use_<?php echo $i; ?>" class="sound_only">모바일사용</label>
										<select name="me_mobile_use[]" id="me_mobile_use_<?php echo $i; ?>">
											<option value="1"<?php echo get_selected($row['me_mobile_use'], '1', true); ?>>사용함</option>
											<option value="0"<?php echo get_selected($row['me_mobile_use'], '0', true); ?>>사용안함</option>
										</select>
									</td>
									<td class="td_mng">
										<?php if(strlen($row['me_code']) == 2) { ?>
										<button type="button" class="btn_add_submenu btn_03 btn btn-primary">추가</button>
										<?php } ?>
										<button type="button" class="btn_del_menu btn_02 btn btn-danger">삭제</button>
									</td>
								</tr>
								<?php
								}

								if ($i==0)
									echo '<tr id="empty_menu_list"><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
								?>
								</tbody>
							</table>
						</div>						
					</div>
					<!-- /.box-body -->
				</div>
				<!-- /.box -->
				</form>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</section>
	<!-- /.content -->
</div>
<!-- /.content-wrapper -->


<script>
$(function() {
    $(document).on("click", ".btn_add_submenu", function() {
        var code = $(this).closest("tr").find("input[name='code[]']").val();
        add_submenu(code);
    });

    $(document).on("click", ".btn_del_menu", function() {
        var $this = $(this),
            $table = $this.closest("table"),
            $tr = $this.closest("tr"),
            code = $tr.find('[name="code[]"]').val();

        if(confirm("메뉴를 삭제하시겠습니까?\n해당 메뉴의 하위메뉴도 모두 삭제됩니다.")) {
            $table.find('[name="code[]"][value^="'+code+'"]').closest('tr').remove();
            if($("#menulist tr.menu_list").size() < 1) {
                var list = '<tr id="empty_menu_list"><td colspan="<?php echo $colspan; ?>" class="empty_table">자료가 없습니다.</td></tr>';
                $("#menulist table tbody").append(list);
            }
            $("#menulist").trigger('menuUpdate');
        } else {
            return false;
        }
        /* 
        if(!confirm("메뉴를 삭제하시겠습니까?"))
            return false;

        var $tr = $(this).closest("tr");
        if($tr.find("td.sub_menu_class").size() > 0) {
            $tr.remove();
        } else {
            var code = $(this).closest("tr").find("input[name='code[]']").val().substr(0, 2);
            $("tr.menu_group_"+code).remove();
        }

        if($("#menulist tr.menu_list").size() < 1) {
            var list = "<tr id=\"empty_menu_list\"><td colspan=\"<?php echo $colspan; ?>\" class=\"empty_table\">자료가 없습니다.</td></tr>\n";
            $("#menulist table tbody").append(list);
        } else {
            $("#menulist tr.menu_list").each(function(index) {
                $(this).removeClass("bg0 bg1")
                    .addClass("bg"+(index % 2));
            });
        } */
    });

    $(document).on('menuUpdate', '#menulist', function(e) {
        var $this = $(this),
            $item = $this.find('[name="me_order[]"]');

        $.each($item, function(i) {
            $(this).val(i);
        });
    });
});

function add_menu(){
    var max_code = base_convert(0, 10, 36);
    $("#menulist tr.menu_list").each(function() {
        var me_code = $(this).find("input[name='code[]']").val().substr(0, 2);
        if(max_code < me_code)
            max_code = me_code;
    });

    var url = "./menu_form.php?code="+max_code+"&new=new";
    window.open(url, "add_menu", "left=100,top=100,width=550,height=650,scrollbars=yes,resizable=yes");
    return false;

    /* 
    var url = "./menu_form.php?new=new";
    window.open(url, "add_menu", "left=100,top=100,width=550,height=650,scrollbars=yes,resizable=yes");
    return false; */
}

function add_submenu(code){
    var url = "./menu_form.php?code="+code;
    window.open(url, "add_menu", "left=100,top=100,width=550,height=650,scrollbars=yes,resizable=yes");
    return false;
}

function base_convert(number, frombase, tobase) {
  //  discuss at: http://phpjs.org/functions/base_convert/
  // original by: Philippe Baumann
  // improved by: Rafał Kukawski (http://blog.kukawski.pl)
  //   example 1: base_convert('A37334', 16, 2);
  //   returns 1: '101000110111001100110100'

  return parseInt(number + '', frombase | 0).toString(tobase | 0);
}

function fmenulist_submit(f){
    return true;
}

function sortMenu(){
    var url = "./menu_sort.php";
    window.open(url, "sortMenu", "left=100,top=100,width=550,height=650,scrollbars=yes,resizable=yes");
    return false;
}

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
	"info" : "_START_ - _END_ (총 _TOTAL_ 개)",
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
  'paging'      : false,
  'lengthChange': false,
  'searching'   : false,
  'ordering'    : false,
  'info'        : true,
  'language' : lang_kor,
  'autoWidth'   : true


	//$('#division1').removeClass('sorting sorting_asc sorting_desc');
		  
	  
	  
	 // [{ 'targets': [0,5,10,11], 'orderable': false }, 	  ]



	});
})



//모든 select 스타일 지정
var selects = document.getElementsByTagName("select"); 
for (var i = 0; i < selects.length; i++) { 
	selects[i].className = "form-control"
}


//required인 form element에 class = "required" 추가
required_elements =	document.getElementById("fmenulist").querySelectorAll("[required]");
for(var i = 0; i < required_elements.length; i++){
	required_elements[i].classList.add("required");
}
</script>

<?php 
include_once ('./admin.tail.php'); 
?>
