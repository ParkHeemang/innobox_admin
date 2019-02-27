<?php
$sub_menu = '500110';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$date = preg_replace('/[^0-9]/i', '', $date);

$date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $date);

$g5['title'] = "$date 일 매출현황";


$sql = " select od_id,
                mb_id,
                od_name,
                od_settle_case,
                od_cart_price,
                od_receipt_price,
                od_receipt_point,
                od_cancel_price,
                od_misu,
                (od_cart_price + od_send_cost + od_send_cost2) as orderprice,
                (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
           from {$g5['g5_shop_order_table']}
          where SUBSTRING(od_time,1,10) = '$date'
          order by od_id desc ";
$result = sql_query($sql);

include_once('../admin.head.php');

//CSS

add_stylesheet('<link rel="stylesheet" href="'.G5_PHP_URL. 'bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 5);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL2.'/bootstrap.custom.css?ver='.G5_CSS_VER.'"></script>',8);


//JS DataTables
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_PHP_URL.'/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);


?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		<?php echo $g5['title']; ?>
		<small>sale today</small>
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
					</div>				
					<!-- /.box-header -->
					<div class="box-body">				
						<div class="tbl_head01 tbl_wrap">
							<table id="example1" class="table table-bordered table-striped dataTable member_list">
								<!-- <caption><?php echo $g5['title']; ?></caption> -->
								<thead>
								<tr>
									<th scope="col">주문번호</th>
									<th scope="col">주문자</th>
									<th scope="col">주문합계</th>
									<th scope="col">쿠폰</th>
									<th scope="col">무통장</th>
									<th scope="col">가상계좌</th>
									<th scope="col">계좌이체</th>
									<th scope="col">카드입금</th>
									<th scope="col">휴대폰</th>
									<th scope="col">포인트입금</th>
									<th scope="col">주문취소</th>
									<th scope="col">미수금</th>
								</tr>
								</thead>
								<tbody>
								<?php
								unset($tot);
								for ($i=0; $row=sql_fetch_array($result); $i++)
								{
									if ($row['mb_id'] == '') { // 비회원일 경우는 주문자로 링크
										$href = '<a href="./orderlist.php?sel_field=od_name&amp;search='.$row['od_name'].'">';
									} else { // 회원일 경우는 회원아이디로 링크
										$href = '<a href="./orderlist.php?sel_field=mb_id&amp;search='.$row['mb_id'].'">';
									}

									$receipt_bank = $receipt_card = $receipt_vbank = $receipt_iche = $receipt_hp = 0;
									if($row['od_settle_case'] == '무통장')
										$receipt_bank = $row['od_receipt_price'];
									if($row['od_settle_case'] == '가상계좌')
										$receipt_vbank = $row['od_receipt_price'];
									if($row['od_settle_case'] == '계좌이체')
										$receipt_iche = $row['od_receipt_price'];
									if($row['od_settle_case'] == '휴대폰')
										$receipt_hp = $row['od_receipt_price'];
									if($row['od_settle_case'] == '신용카드')
										$receipt_card = $row['od_receipt_price'];

								?>
									<tr>
										<td class="td_alignc"><a href="./orderform.php?od_id=<?php echo $row['od_id']; ?>"><?php echo $row['od_id']; ?></a></td>
										<td class="td_name"><?php echo $href; ?><?php echo $row['od_name']; ?></a></td>
										<td class="td_numsum"><?php echo number_format($row['orderprice']); ?></td>
										<td class="td_numincome"><?php echo number_format($row['couponprice']); ?></td>
										<td class="td_numincome"><?php echo number_format($receipt_bank); ?></td>
										<td class="td_numincome"><?php echo number_format($receipt_vbank); ?></td>
										<td class="td_numincome"><?php echo number_format($receipt_iche); ?></td>
										<td class="td_numincome"><?php echo number_format($receipt_card); ?></td>
										<td class="td_numincome"><?php echo number_format($receipt_hp); ?></td>
										<td class="td_numincome"><?php echo number_format($row['od_receipt_point']); ?></td>
										<td class="td_numcancel1"><?php echo number_format($row['od_cancel_price']); ?></td>
										<td class="td_numrdy"><?php echo number_format($row['od_misu']); ?></td>
									</tr>
								<?php
									$tot['orderprice']    += $row['orderprice'];
									$tot['ordercancel']   += $row['od_cancel_price'];
									$tot['coupon']        += $row['couponprice'] ;
									$tot['receipt_bank']  += $receipt_bank;
									$tot['receipt_vbank'] += $receipt_vbank;
									$tot['receipt_iche']  += $receipt_iche;
									$tot['receipt_card']  += $receipt_card;
									$tot['receipt_hp']    += $receipt_hp;
									$tot['receipt_point'] += $row['od_receipt_point'];
									$tot['misu']          += $row['od_misu'];
								}

								if ($i == 0) {
									echo '<tr><td colspan="12" class="empty_table">자료가 없습니다</td></tr>';
								}
								?>
								</tbody>
								<tfoot>
								<tr>
									<td colspan="2">합 계</td>
									<td class="td_num_right"><?php echo number_format($tot['orderprice']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['coupon']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['receipt_bank']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['receipt_vbank']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['receipt_iche']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['receipt_card']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['receipt_hp']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['receipt_point']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['ordercancel']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['misu']); ?></td>
								</tr>
								</tfoot>
							</table>
						</div>
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
$(function () {
	 

    // Korean
    var lang_kor = {
        "decimal" : "",
        "emptyTable" : "데이터가 없습니다.",
        "info" : "_START_ - _END_ (총 _TOTAL_ 개)",
        "infoEmpty" : "0명",
        "infoFiltered" : "(전체 _MAX_ 개 중 검색결과)",
        "infoPostFix" : "",
        "thousands" : ",",
        "lengthMenu" : "_MENU_ 개",
        "loadingRecords" : "로딩중...",
        "processing" : "처리중...",
        "search" : "검색:",
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
	  'order': [  [2, 'asc']],
      'info'        : false,
	  'language' : lang_kor,
      'autoWidth'   : false,
	  'columnDefs': [{
            'bSortable': false,
            'aTargets': [1]
        }]
    });


  })
</script>



<?php
include_once ('../footer.php');
?>
