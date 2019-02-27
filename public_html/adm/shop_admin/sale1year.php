<?php
$sub_menu = '500110';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$fr_year = preg_replace('/[^0-9]/i', '', $fr_year);
$to_year = preg_replace('/[^0-9]/i', '', $to_year);

$g5['title'] = $fr_year.' ~ '.$to_year.' 연간 매출현황';
include_once ('../admin.head.php');

function print_line($save)
{
    ?>
    <tr>
        <td class="td_alignc"><a href="./sale1month.php?fr_month=<?php echo $save['od_date']; ?>01&amp;to_month=<?php echo $save['od_date']; ?>12"><?php echo $save['od_date']; ?></a></td>
        <td class="td_num"><?php echo number_format($save['ordercount']); ?></td>
        <td class="td_numsum"><?php echo number_format($save['orderprice']); ?></td>
        <td class="td_numcoupon"><?php echo number_format($save['ordercoupon']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptbank']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptvbank']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptiche']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptcard']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receipthp']); ?></td>
        <td class="td_numincome"><?php echo number_format($save['receiptpoint']); ?></td>
        <td class="td_numcancel1"><?php echo number_format($save['ordercancel']); ?></td>
        <td class="td_numrdy"><?php echo number_format($save['misu']); ?></td>
    </tr>
    <?php
}

$sql = " select od_id,
                SUBSTRING(od_time,1,4) as od_date,
                od_send_cost,
                od_settle_case,
                od_receipt_price,
                od_receipt_point,
                od_cart_price,
                od_cancel_price,
                od_misu,
                (od_cart_price + od_send_cost + od_send_cost2) as orderprice,
                (od_cart_coupon + od_coupon + od_send_coupon) as couponprice
           from {$g5['g5_shop_order_table']}
          where SUBSTRING(od_time,1,4) between '$fr_year' and '$to_year'
          order by od_time desc ";
$result = sql_query($sql);



//CSS

add_stylesheet('<link rel="stylesheet" href="'.G5_PHP_URL. 'bower_components/bootstrap/dist/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 5);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL2.'/bootstrap.custom.css?ver='.G5_CSS_VER.'">',8);


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
		<small>yearly sale</small>
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
									<th scope="col">주문년도</th>
									<th scope="col">주문수</th>
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
								unset($save);
								unset($tot);
								for ($i=0; $row=sql_fetch_array($result); $i++)
								{
									if ($i == 0)
										$save['od_date'] = $row['od_date'];

									if ($save['od_date'] != $row['od_date']) {
										print_line($save);
										unset($save);
										$save['od_date'] = $row['od_date'];
									}

									$save['ordercount']++;
									$save['orderprice']    += $row['orderprice'];
									$save['ordercancel']   += $row['od_cancel_price'];
									$save['ordercoupon']   += $row['couponprice'];
									if($row['od_settle_case'] == '무통장')
										$save['receiptbank']   += $row['od_receipt_price'];
									if($row['od_settle_case'] == '가상계좌')
										$save['receiptvbank']   += $row['od_receipt_price'];
									if($row['od_settle_case'] == '계좌이체')
										$save['receiptiche']   += $row['od_receipt_price'];
									if($row['od_settle_case'] == '휴대폰')
										$save['receipthp']   += $row['od_receipt_price'];
									if($row['od_settle_case'] == '신용카드')
										$save['receiptcard']   += $row['od_receipt_price'];
									$save['receiptpoint']  += $row['od_receipt_point'];
									$save['misu']          += $row['od_misu'];

									$tot['ordercount']++;
									$tot['orderprice']    += $row['orderprice'];
									$tot['ordercancel']   += $row['od_cancel_price'];
									$tot['ordercoupon']   += $row['couponprice'];
									if($row['od_settle_case'] == '무통장')
										$tot['receiptbank']   += $row['od_receipt_price'];
									if($row['od_settle_case'] == '가상계좌')
										$tot['receiptvbank']   += $row['od_receipt_price'];
									if($row['od_settle_case'] == '계좌이체')
										$tot['receiptiche']   += $row['od_receipt_price'];
									if($row['od_settle_case'] == '휴대폰')
										$tot['receipthp']   += $row['od_receipt_price'];
									if($row['od_settle_case'] == '신용카드')
										$tot['receiptcard']   += $row['od_receipt_price'];
									$tot['receiptpoint']  += $row['od_receipt_point'];
									$tot['misu']          += $row['od_misu'];
								}

								if ($i == 0) {
									echo '<tr><td colspan="12" class="empty_table">자료가 없습니다.</td></tr>';
								} else {
									print_line($save);
								}
								?>
								</tbody>
								<tfoot>
								<tr>
									<td>합 계</td>
									<td class="td_num_right"><?php echo number_format($tot['ordercount']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['orderprice']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['ordercoupon']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['receiptbank']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['receiptvbank']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['receiptiche']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['receiptcard']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['receipthp']); ?></td>
									<td class="td_num_right"><?php echo number_format($tot['receiptpoint']); ?></td>
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
