<?php
$sub_menu = '300700';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

//dbconfig파일에 $g5['faq_table'] , $g5['faq_master_table'] 배열변수가 있는지 체크
if( !isset($g5['faq_table']) || !isset($g5['faq_master_table']) ){
    die('<meta charset="utf-8">/data/dbconfig.php 파일에 <br ><strong>$g5[\'faq_table\'] = G5_TABLE_PREFIX.\'faq\';</strong><br ><strong>$g5[\'faq_master_table\'] = G5_TABLE_PREFIX.\'faq_master\';</strong><br > 를 추가해 주세요.');
}

//자주하시는 질문 마스터 테이블이 있는지 검사한다.
if(!sql_query(" DESCRIBE {$g5['faq_master_table']} ", false)) {
    if(sql_query(" DESCRIBE {$g5['g5_shop_faq_master_table']} ", false)) {
        sql_query(" ALTER TABLE {$g5['g5_shop_faq_master_table']} RENAME TO `{$g5['faq_master_table']}` ;", false);
    } else {
       $query_cp = sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['faq_master_table']}` (
                      `fm_id` int(11) NOT NULL AUTO_INCREMENT,
                      `fm_subject` varchar(255) NOT NULL DEFAULT '',
                      `fm_head_html` text NOT NULL,
                      `fm_tail_html` text NOT NULL,
                      `fm_order` int(11) NOT NULL DEFAULT '0',
                      PRIMARY KEY (`fm_id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ", true);
    }
    // FAQ Master
    sql_query(" insert into `{$g5['faq_master_table']}` set fm_id = '1', fm_subject = '자주하시는 질문' ", false);
}

//자주하시는 질문 테이블이 있는지 검사한다.
if(!sql_query(" DESCRIBE {$g5['faq_table']} ", false)) {
    if(sql_query(" DESCRIBE {$g5['g5_shop_faq_table']} ", false)) {
        sql_query(" ALTER TABLE {$g5['g5_shop_faq_table']} RENAME TO `{$g5['faq_table']}` ;", false);
    } else {
       $query_cp = sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['faq_table']}` (
                      `fa_id` int(11) NOT NULL AUTO_INCREMENT,
                      `fm_id` int(11) NOT NULL DEFAULT '0',
                      `fa_subject` text NOT NULL,
                      `fa_content` text NOT NULL,
                      `fa_order` int(11) NOT NULL DEFAULT '0',
                      PRIMARY KEY (`fa_id`),
                      KEY `fm_id` (`fm_id`)
                    ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ", true);
    }
}

$g5['title'] = 'FAQ관리';
include_once ('./admin.head.php');

$sql_common = " from {$g5['faq_master_table']} ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = "select * $sql_common order by fm_order, fm_id limit $from_record, {$config['cf_page_rows']} ";
$result = sql_query($sql);

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
	  <h1>
		FAQ관리
		<small>faqmasterlist</small>
	  </h1>
	  <ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li><a href="#">Tables</a></li>
		<li class="active">Data tables</li>
	  </ol>
	</section>
	<!-- Main content -->
	<section class="content">
		<div class="row">
			<div class="col-xs-12">          
				<div class="box">
					<div class="box-header">
						<nav class="navbar navbar-default navbtn">
							<div class="text-right">							
							<a href="./faqmasterform.php" class="btn btn-primary">FAQ추가</a>					
							</div>
						</nav>  
					</div>
					<!-- /.box-header -->
					<div class="box-body">						
						<table id="example1" class="table table-bordered table-striped dataTable member_list" width="100%">
						<!-- 	<caption><?php echo $g5['title']; ?> 목록</caption> -->
						<thead>
						<tr>
							<th scope="col" style="width:5%">ID</th>
							<th scope="col" style="width: 75%">제목</th>
							<th scope="col">FAQ수</th>
							<th scope="col">순서</th>
							<th scope="col">관리</th>
						</tr>
						</thead>
						<tbody>
						<?php for ($i=0; $row=sql_fetch_array($result); $i++) {
							$sql1 = " select COUNT(*) as cnt from {$g5['faq_table']} where fm_id = '{$row['fm_id']}' ";
							$row1 = sql_fetch($sql1);
							$cnt = $row1['cnt'];
							$bg = 'bg'.($i%2);
						?>
						<tr class="<?php echo $bg; ?>">
							<td class="td_num"><?php echo $row['fm_id']; ?></td>
							<td class="td_left"><a href="./faqlist.php?fm_id=<?php echo $row['fm_id']; ?>&amp;fm_subject=<?php echo $row['fm_subject']; ?>"><?php echo stripslashes($row['fm_subject']); ?></a></td>
							<td class="td_num"><?php echo $cnt; ?></td>
							<td class="td_num"><?php echo $row['fm_order']?></td>
							<td class="td_mng td_mng_l">
								<a href="./faqmasterform.php?w=u&amp;fm_id=<?php echo $row['fm_id']; ?>" class="btn btn-success"><!-- <span class="sound_only"><?php echo stripslashes($row['fm_subject']); ?> </span> -->수정</a>
								<a href="<?php echo G5_BBS_URL; ?>/faq.php?fm_id=<?php echo $row['fm_id']; ?>" class="btn btn-primary"><!-- <span class="sound_only"><?php echo stripslashes($row['fm_subject']); ?> </span> -->보기</a>
								<a href="./faqmasterformupdate.php?w=d&amp;fm_id=<?php echo $row['fm_id']; ?>" onclick="return delete_confirm(this);" class="btn btn-danger"><!-- <span class="sound_only"><?php echo stripslashes($row['fm_subject']); ?> </span> -->삭제</a>
							</td>
						</tr>
						<?php
						}

						if ($i == 0){
							echo '<tr><td colspan="5" class="empty_table"><span>자료가 한건도 없습니다.</span></td></tr>';
						}
						?>
						</tbody>
						</table>						
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
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
	  'order': [  [0, 'desc']],
      'info'        : true,
	  'language' : lang_kor,
      'autoWidth'   : true,
	  'columnDefs': [{
            'bSortable': false,
            'aTargets': [4]
        }]





    });
 })


</script>


<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<?php
include_once ('./admin.tail.php');
?>
