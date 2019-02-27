<?php
$sub_menu = "900500";
include_once("./_common.php");

$colspan = 5;

auth_check($auth[$sub_menu], "r");

$g5['title'] = "이모티콘 그룹";

$res = sql_fetch("select count(*) as cnt from {$g5['sms5_form_group_table']}");
$total_count = $res['cnt'];

$group = array();
$qry = sql_query("select * from {$g5['sms5_form_group_table']} order by fg_name");
while ($res = sql_fetch_array($qry)) array_push($group, $res);

include_once(G5_ADMIN_PATH.'/admin.head.php');

//CSS
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL. '/css/dataTables.bootstrap.min.css?ver='.G5_CSS_VER.'">', 4);
//JS DataTables
add_javascript('<script src="'.G5_ADMIN_URL.'/js/jquery.dataTables.min.js?ver='.G5_JS_VER.'"></script>', 4);
add_javascript('<script src="'.G5_ADMIN_URL.'/js/dataTables.bootstrap.min.js?ver='.G5_JS_VER.'"></script>', 5);

?>


<script>

function move(fg_no, fg_name, sel) {
    var msg = '';
    if (sel.value)
    {
        msg  = "'" + fg_name + "' 그룹에 속한 모든 데이터를\n\n'";
        msg += sel.options[sel.selectedIndex].text + "' 그룹으로 이동하시겠습니까?";

        if (confirm(msg))
            location.href = 'form_group_move.php?fg_no=' + fg_no + '&move_no=' + sel.value;
        else
            sel.selectedIndex = 0;
    }
}

function empty(fg_no) {
    if (confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n그룹에 속한 데이터를 정말로 비우시겠습니까?"))
        location.href = 'form_group_update.php?w='+ fg_no +'&fg_no=' + fg_no;
}

function grouplist_submit(f)
{
    if (!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n삭제되는 그룹에 속한 자료는 '미분류'로 이동됩니다.\n\n그래도 삭제하시겠습니까?")) {
            f.w.value = "de";
        } else {
            return false;
        }
    }

    if(document.pressed == "선택비우기") {
        if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n그룹에 속한 데이터를 정말로 비우시겠습니까?")) {
            f.w.value = "em";
        } else {
            return false;
        }
    }

    return true;
}

</script>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

	<!-- Content Header (Page header) -->    
	<section class="content-header">
		<h1 class="member_list_title">
		이모티콘 그룹
		<small>form group</small>
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
							
		
							<div class="row">
								<div class="col-xs-6 text-left">							
									   <label for="fg_name" class="m-0">그룹명<!-- <strong class="sound_only"> 필수</strong> --></label>
										<input type="text" id="fg_name" name="fg_name" required class="required frm_input form-control">
										<input type="submit" value="추가" class="btn_submit btn btn-danger">				
								</div>
								<div class="col-xs-6 text-right">
									<input type="submit" name="act_button" value="선택수정" onclick="document.pressed=this.value" class="btn btn-info">
									<input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn-danger">
									<input type="submit" name="act_button" value="선택비우기" onclick="document.pressed=this.value" class="btn btn-secondary">
								</div>
							</div>
				
							
						</nav>
					</div>			
					<!-- /.box-header -->

					<div class="box-body">
						<form name="group<?php echo $res['fg_no']?>" method="post" action="./form_group_update.php">
						<input type="hidden" name="fg_no" value="<?php echo $res['fg_no']?>">
						</form>

						<div class="local_ov01 local_ov">
						<span class="btn_ov01"><span class="ov_txt"> 건수  </span><span class="ov_num"><?php echo number_format($total_count)?></span></span>
						</div>

						<div class="local_desc01 local_desc">
						<p>그룹명순으로 정렬됩니다.</p>
						</div>

						<form name="group<?php echo $group[$i]['fg_no']?>" method="post" action="./form_group_update.php" onsubmit="return grouplist_submit(this);">
						<input type="hidden" name="w" value="u">

						
						<table id="example1" class="table table-bordered table-striped dataTable member_list" width="100%">
							<!-- <caption><?php echo $g5['title']; ?> 목록</caption> -->
							<thead>
							<tr>
								<th scope="col">
									<!-- <label for="chkall" class="sound_only">그룹 전체</label> -->
									<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
								</th>
								<th scope="col">그룹명</th>
								<th scope="col">메시지수</th>
								<th scope="col">이동</th>
								<th scope="col">보기</th>
							</tr>
							</thead>
							<tbody>
							<?php
							$qry = sql_query("select count(*) as cnt from {$g5['sms5_form_table']} where fg_no=0");
							$res = sql_fetch_array($qry);
							?>
							<tr>
								<td></td>
								<td>미분류</td>
								<td class="td_numbig"><?php echo number_format($res['cnt'])?></td>
								<td class="td_mng">
								  
									<select name="select_fg_no_999" id="select_fg_no_999" onchange="move(0, '미분류', this);" class="form-control">
										<option value=""></option>
										<?php for ($i=0; $i<count($group); $i++) { ?>
										<option value="<?php echo $group[$i]['fg_no']?>"> <?php echo $group[$i]['fg_name']?> </option>
										<?php } ?>
									</select>
								</td>
								<td class="td_mng">
									<a href="./form_list.php?fg_no=0" class="btn btn-info">보기</a>
									<!-- <button type="button" onclick="empty('no');">비우기</button> -->
								</td>
							</tr>
							<?php
							for ($i=0; $i<count($group); $i++) {
								$bg = 'bg'.(($i + 1)%2);
							?>
							<tr class="<?php echo $bg; ?>">
								<td class="td_mng">
									<input type="hidden" name="fg_no[<?php echo $i ?>]" value="<?php echo $group[$i]['fg_no']?>" id="fg_no_<?php echo $i ?>">
									<label for="chk_<?php echo $i ?>" class="sound_only"><?php echo $group[$i]['fg_name']?></label>
									<input type="checkbox" name="chk[]" value="<?php echo $i ?>" id="chk_<?php echo $i ?>">
								</td>
								<td>
									<label for="fg_name_<?php echo $i; ?>" class="sound_only">그룹명</label>
									<input type="text" name="fg_name[<?php echo $i; ?>]" value="<?php echo $group[$i]['fg_name']?>" id="fg_name_<?php echo $i; ?>" class="frm_input">
									<input type="checkbox" name="fg_member[<?php echo $i; ?>]" value="1" id="fg_member_<?php echo $i; ?>" <?php if ($group[$i]['fg_member']) echo 'checked';?>>
									<label for="fg_member_<?php echo $i; ?>">회원</label>
								</td>
								<td class="td_numbig">
									<?php echo number_format($group[$i]['fg_count'])?>
								</td>
								<td class="td_mng">
									<label for="select_fg_no_<?php echo $i; ?>" class="sound_only">그룹명</label>
									<select name="select_fg_no[<?php echo $i; ?>]" id="select_fg_no_<?php echo $i; ?>" onchange="move(<?php echo $group[$i]['fg_no']?>, '<?php echo $group[$i]['fg_name']?>', this);">
										<option value=''></option>
										<option value='0'>미분류</option>
										<?php for ($j=0; $j<count($group); $j++) { ?>
										<?php if ($group[$i]['fg_no']==$group[$j]['fg_no']) continue; ?>
										<option value="<?php echo $group[$j]['fg_no']?>"> <?php echo $group[$j]['fg_name']?></option>
										<?php } ?>
									</select>
								</td>
								<td class="td_mng">
									<a href="./form_list.php?fg_no=<?php echo $group[$i]['fg_no']?>">보기</a>
								</td>
							</tr>
							<?php } ?>
							</tbody>
						</table>

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


<?php
include_once(G5_ADMIN_PATH.'/footer.php');
?>

