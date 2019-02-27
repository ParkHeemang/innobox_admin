<!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
		<!-- Content Header (Page header) -->    
		<section class="content-header">
		  <h1 class="member_list_title">
			회원관리
			<small>member list</small>
		  </h1>
		  <ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">회원관리</a></li>
			<li class="active">회원정보리스트</li>
		  </ol>
		</section>
		<!-- Main content -->
		<form name="member_delete" method="post" action="./member_list_update.php" onsubmit="return fmemberlist_submit(this);">
		<section class="content">
		  <div class="row">
			<div class="col-xs-12">
			  <div class="box">
				<div class="box-header">
					<nav class="navbar navbar-default navbtn" style="z-index: 1000000000000000000000000000000000">
						<div class="text-right">							
							<button type="button" class="btn btn-info btn-flat btn-list"><i class="fa fa-fw fa-list"></i>목록</button>
							<button type="button" class="btn btn-success btn-flat"><i class="fa fa-fw fa-edit"></i>선택수정</button>
							<!-- input type="button" id="memeber_delete" class="btn btn-danger btn-flat" name="chk_" value="선택삭제" onclick="document.pressed='delete';" -->  
							<button type="submit" onclick="document.pressed=this.value;" value="선택삭제" class="btn btn-danger btn-flat"><i class="fa fa-fw fa-trash"></i>선택삭제</button>
							<button type="button" class="btn btn-primary btn-flat" onclick="location.href='./member_form.php'"> <i class="fa fa-fw fa-plus-square"></i>회원추가</button>						
						</div>
					</nav>
				</div>
				
				<!-- /.box-header -->
				<div class="box-body">					
					







				</div>
				<!-- /.box-body -->
			  </div>


			  <!-- /.box -->
			</div>
			<!-- /.col -->
		  </div>
		  <!-- /.row -->
		  </form>
		</section>
		<!-- /.content -->
	
	</div>
	<!-- /.content-wrapper -->










	
<?php

include_once ('./footer.php');
?>




