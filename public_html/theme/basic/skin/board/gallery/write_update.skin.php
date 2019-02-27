<?php

$wr_2 = $_POST['wr_tag'];

$wr_2_Arr = explode(",", $wr_2);

for ($i=0; $i < count($wr_2_Arr); $i++) {

	$sql = "select count(*) as cnt
						from " . $g5['tag_table'] . "
					where tag_name='" . $wr_2_Arr[$i] . "' ";

	$row = sql_fetch($sql);

	if ($row['cnt'] == 0)
	{
		$sql = "insert into " . $g5['tag_table'] . "
							set tag_name ='" . $wr_2_Arr[$i] . "',
									tag_datetime ='" . G5_TIME_YMDHIS . "' ";

		sql_query($sql);
	}
}

$sql = "update " . $write_table . "
					set wr_2 ='" . $wr_2 . "'
				where wr_id ='" . $wr_id . "' ";

sql_query($sql);
?>