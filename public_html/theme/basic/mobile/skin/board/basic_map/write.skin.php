<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// add_stylesheet('css 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_stylesheet('<link rel="stylesheet" href="'.$board_skin_url.'/style.css">', 0);
// 구글지도 처음화면 설정
if($write['wr_8'] == null){$write['wr_8'] =  35.145228;}
if($write['wr_9'] == null){$write['wr_9'] = 126.84183389999998;}
?>

<?php
    if ($bo_table == 'notice') {
        echo '<img src="'.G5_THEME_IMG_URL.'/m_cs_notice.jpg" width="100%" height="auto">';
    }
    if ($bo_table == 'reservation') {
        echo '<img src="'.G5_THEME_IMG_URL.'/m_cs_news.jpg" width="100%" height="auto">';
    }
    if ($bo_table == 'cscenter') {
        echo '<img src="'.G5_THEME_IMG_URL.'/m_cs_cscenter.jpg" width="100%" height="auto">';
    }
    if ($bo_table == 'notice' || $bo_table == 'reservation' || $bo_table == 'cscenter' ) {
        echo '<div id="m_cs_tab_wrap">';
        echo '<img src="'.G5_THEME_IMG_URL.'/m_customer_service_title.jpg" width="100%" height="auto">';
        echo '<ul>';
        echo '<li><a href="'.G5_BBS_URL.'/board.php?bo_table=notice">공지사항</a></li>';
        echo '<li><a href="'.G5_BBS_URL.'/board.php?bo_table=reservation">방문요청</a></li>';
        echo '<li><a href="'.G5_BBS_URL.'/write.php?bo_table=cscenter">견적상담</a></li>';
        echo '</ul>';
        echo '</div>';
    }
    if ($bo_table == 'maintenance') {
        echo '<img src="'.G5_THEME_IMG_URL.'/m_maintenance_img1.jpg" width="100%" height="auto">';
        echo '<div id="maintenance_wrap">';
        echo '<img src="'.G5_THEME_IMG_URL.'/m_maintenance_img4.jpg" width="100%" height="auto">';
        echo '<div id="maintenance_btn">';
        echo '<a href="'.G5_BBS_URL.'/content.php?co_id=maintenance"><img src="'.G5_THEME_IMG_URL.'/m_maintenance_img2.jpg"></a>';
        echo '<a href="'.G5_BBS_URL.'/board.php?bo_table=maintenance"><img src="'.G5_THEME_IMG_URL.'/m_maintenance_img3.jpg"></a>';
        echo'</div>';
        echo'</div>';
    }

?>

<!-- 구글지도 추가 -->
<div id="googlmap">
    <script src="https://maps.google.com/maps/api/js?sensor=true&key=AIzaSyBKN8UAci3v2GLnHkSWq1iXzsoEj_0PW8c"></script>
    <script type="text/javascript">
    var map;
    var geocoder;
    var marker;
    var ymakerimg = '<?php echo $board_skin_url ?>/img/map.png';

    function mgminfomap(){
  geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng('<?=$write[wr_8]?>','<?=$write[wr_9]?>'); //초기화면부분

  var googlmapOption = {
      zoom : 14,
      center: latlng,
      
      mapTypeId: google.maps.MapTypeId.ROADMAP
  }
      
  map = new google.maps.Map(document.getElementById('googlmap'), googlmapOption);
  
  marker = new google.maps.Marker({
      position:latlng,
      icon : ymakerimg,
      map:map
      });
  
  // 이동시 좌표와 주소 변경이벤트
  google.maps.event.addListener(map, 'dragend', function(){    
    moveLatlng();
    changeAddress();
    });
    };

    // 이동시 좌표구하기  
    function moveLatlng(){
    var mll=map.getCenter();
    document.getElementById('wr_8').value = mll.lat();
    document.getElementById('wr_9').value = mll.lng();
    addMark(mll.lat(), mll.lng()); 
    }

    function changeAddress(){
    geocoder.geocode( { 'location': map.getCenter()}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
        
    var str="";
    for(var i=3; i>=0; i--){
    str += " "+results[0].address_components[i].short_name;
    }
    document.getElementById('wr_10').value=str;
    } else {
    alert("주소를 찾지 못했습니다 " + status);
    }
    });
    }

    function codeAddress(){
  var address = document.getElementById('wr_10').value;
  // 주소입력안했을때 경고창
  if(address=='검색할 주소를 입력하십시오.' || address==''){
    alert('검색할 주소를 입력하십시오.');
    document.getElementById("wr_10").value='';
    document.getElementById("wr_10").focus();
    return;
    }
  
    geocoder.geocode({'address':address}, function(results, status){
      if(status == google.maps.GeocoderStatus.OK){
          map.setCenter(results[0].geometry.location);
          addMark(results[0].geometry.location.lat(), results[0].geometry.location.lng()); 
            document.getElementById('wr_8').value = results[0].geometry.location.lat();
            document.getElementById('wr_9').value = results[0].geometry.location.lng();
            
      }else{
          alert('주소를 찾지 못했습니다 :'+status);
        }
        
     });
    
    }
    function addMark(lat,lng){  
    if(typeof marker!='undefined'){  
    marker.setMap(null);  
    }  
  
    marker = new google.maps.Marker({  
    map: map,
    icon : ymakerimg,
    position: new google.maps.LatLng(lat,lng)  
    });
var infowindow = new google.maps.InfoWindow({
    content: '위도: ' + lat + '<br>경도: ' + lng
    });
    infowindow.open(map,marker);
    } 
    function moveMarker(lat, lng){
    marker.setPosition(lat, lng);

    }

    function mypoint(){
// 나의위치 찾아보기
    if (navigator.geolocation) {
    browserSupportFlag = true;
    navigator.geolocation.getCurrentPosition(function(position){
    var initialLocation = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
    // 좌표를 주소로변환						
    geocoder.geocode({'latLng' : initialLocation}, function(results, status) 
    {
    if (status == google.maps.GeocoderStatus.OK) {
    if (results[1]) {

    document.getElementById('wr_10').value = results[3].formatted_address;
    }
    } else {
    alert("위치를 찾지 못했습니다: " + status);
    }
    });
            document.getElementById('wr_8').value = position.coords.latitude;
            document.getElementById('wr_9').value = position.coords.longitude;
            
                    map.setCenter(initialLocation);
                    marker.setPosition(initialLocation);
                }, function(){
                    handleNoGeolocation(browserSupportFlag);
                });
                
            }
            else if (google.gears) { 
                    browserSupportFlag = true;
                    var geo = google.gears.factory.create('beta.geolocation');
                    geo.getCurrentPosition(function(position){
                        var initialLocation = new google.maps.LatLng(position.latitude, position.longitude);
                        
                        // 좌표를 주소로변환						
                    geocoder.geocode({'latLng' : initialLocation}, function(results, status) 
    {
    if (status == google.maps.GeocoderStatus.OK) {
    if (results[1]) {
    document.getElementById('wr_10').value = results[3].formatted_address;
    }
    } else {
    alert("위치를 찾지 못했습니다: " + status);
    }
    });
                    
    document.getElementById('wr_8').value = position.coords.latitude;
    document.getElementById('wr_9').value = position.coords.longitude;
    map.setCenter(initialLocation);
    marker.setPosition(initialLocation);
    }, function(){
    handleNoGeoLocation(browserSupportFlag);
    });
     // Browser doesn't support Geolocation
    }
    else {
    browserSupportFlag = false;
    handleNoGeolocation(browserSupportFlag);
    }
    }
    google.maps.event.addDomListener(window, 'load', mgminfomap);
    </script>
</div>
<!-- //구글지도 추가 -->

<div id="bbs" class="bbs_<?php echo $bo_table; ?>">
    <section id="bo_w">

        <form name="fwrite" id="fwrite" action="<?php echo $action_url ?>" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
        <input type="hidden" name="w" value="<?php echo $w ?>">
        <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>">
        <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>">
        <input type="hidden" name="sca" value="<?php echo $sca ?>">
        <input type="hidden" name="sfl" value="<?php echo $sfl ?>">
        <input type="hidden" name="stx" value="<?php echo $stx ?>">
        <input type="hidden" name="spt" value="<?php echo $spt ?>">
        <input type="hidden" name="sst" value="<?php echo $sst ?>">
        <input type="hidden" name="sod" value="<?php echo $sod ?>">
        <input type="hidden" name="page" value="<?php echo $page ?>">
        <input type="hidden" name="wr_7" value="<?php echo $member['mb_hp'] ?>">
        <?php
        $option = '';
        $option_hidden = '';
        if ($is_notice || $is_html || $is_secret || $is_mail) {
            $option = '';
            if ($is_notice) {
                $option .= PHP_EOL.'<input type="checkbox" id="notice" name="notice" value="1" '.$notice_checked.'>'.PHP_EOL.'<label for="notice">공지</label>';
            }

            if ($is_html) {
                if ($is_dhtml_editor) {
                    $option_hidden .= '<input type="hidden" value="html1" name="html">';
                } else {
                    //$option .= PHP_EOL.'<input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" value="'.$html_value.'" '.$html_checked.'>'.PHP_EOL.'<label //for="html">html</label>';
                    $option .= PHP_EOL.'<input type="checkbox" id="html" name="html" onclick="html_auto_br(this);" value="'.$html_value.'" checked="">'.PHP_EOL.'<label for="html">html</label>';
                }
            }

            if ($is_secret) {
                if ($is_admin || $is_secret==1) {
                    $option .= PHP_EOL.'<input type="checkbox" id="secret" name="secret" value="secret" '.$secret_checked.'>'.PHP_EOL.'<label for="secret">비밀글</label>';
                } else {
                    $option_hidden .= '<input type="hidden" name="secret" value="secret">';
                }
            }

            if ($is_mail) {
                $option .= PHP_EOL.'<input type="checkbox" id="mail" name="mail" value="mail" '.$recv_email_checked.'>'.PHP_EOL.'<label for="mail">답변메일받기</label>';
            }
        }

        echo $option_hidden;
        ?>
        <div class="tbl_frm01 tbl_wrap">
            <table>
            <caption><?php echo $g5['title'] ?></caption>
            <tbody>
            <?php if ($is_name) { ?>
            <tr>
                <th scope="row"><label for="wr_name">이름<strong class="sound_only">필수</strong></label></th>
                <td><input type="text" name="wr_name" value="<?php echo $name ?>" id="wr_name" required class="frm_input required" maxlength="20"></td>
            </tr>
            <?php } ?>

            <?php if ($is_password) { ?>
            <tr>
                <th scope="row"><label for="wr_password">비밀번호<strong class="sound_only">필수</strong></label></th>
                <td><input type="password" name="wr_password" id="wr_password" <?php echo $password_required ?> class="frm_input <?php echo $password_required ?>" maxlength="20"></td>
            </tr>
            <?php } ?>

            <tr>
                <th scope="row"><label for="wr_email">이메일</label></th>
                <td><input type="email" name="wr_email" value="<?php echo $email ?>" id="wr_email" class="frm_input email" maxlength="100"></td>
            </tr>


            <?php if ($option) { ?>
            <tr>
                <th scope="row">옵션</th>
                <td><?php echo $option ?></td>
            </tr>
            <?php } ?>

            <?php if ($is_category) { ?>
            <tr>
                <th scope="row"><label for="ca_name">분류<strong class="sound_only">필수</strong></label></th>
                <td>
                    <select class="required" id="ca_name" name="ca_name" required>
                        <option value="">선택하세요</option>
                        <?php echo $category_option ?>
                    </select>
                </td>
            </tr>
            <?php } ?>

            <tr>
                <th scope="row"><label for="wr_subject">제목<strong class="sound_only">필수</strong></label></th>
                <td><input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="frm_input required"></td>
            </tr>
            <tr>
                <th scope="row"><label for="wr_10">주소<strong class="sound_only">필수</strong></label></th>
                <td colspan="3">
                    <input type="text" name="wr_10" value="<?php echo $write['wr_10'] ?>" id="wr_10" class="frm_input required" required size="20" placeholder="주소" onKeyDown="if(event.keyCode==13){codeAddress();}"> 
                    <input type="hidden" name="wr_8" value="<?php echo $write['wr_8'] ?>" id="wr_8" class="frm_input full_input">
                    <input type="hidden" name="wr_9" value="<?php echo $write['wr_9'] ?>" id="wr_9" class="frm_input full_input">
                    <!--
                    <input type="submit" value="검색" id="btn_submit" class="btn_submit2 btn" onclick="codeAddress()">
                    -->
                    <a href="#none" onclick="codeAddress()">검색</a>
                </td>
            </tr>
            <tr>
            <th scope="row"><label for="wr_1">희망일<strong class="sound_only">필수</strong></label></th>
            <td>
                <input type="text" name="wr_1" class="datepicker required frm_input" required value="<?php echo $write['wr_1']?>">
                <select name="wr_2" class="frm_input"  required>
                    <?php
                        for ($i=0; $i<24; $i++) {
                            echo '<option value="'.sprintf('%02d',$i).'" '.get_selected($i,$write['wr_2']).'>'.sprintf('%02d',$i).'</option>';
                        }
                    ?>
                </select>
                <select name="wr_3" class="frm_input"  required>
                    <?php
                        for ($i=0; $i<=59; $i++) {
                            echo '<option value="'.sprintf('%02d',$i).'" '.get_selected($i,$write['wr_3']).'>'.sprintf('%02d',$i).'</option>';
                        }
                    ?>
                </select>
            </td>
        </tr>
        <script>
        $(document).ready(function(){
            // 시작일,종료일
            $(".datepicker").datepicker({ 
                dateFormat: "yy-mm-dd", 
                showButtonPanel: true, 
            });
        });
        </script>

            <tr>
                <th scope="row"><label for="wr_content">내용<strong class="sound_only">필수</strong></label></th>
                <td class="wr_content">
                    <?php if($write_min || $write_max) { ?>
                    <!-- 최소/최대 글자 수 사용 시 -->
                    <p id="char_count_desc">이 게시판은 최소 <strong><?php echo $write_min; ?></strong>글자 이상, 최대 <strong><?php echo $write_max; ?></strong>글자 이하까지 글을 쓰실 수 있습니다.</p>
                    <?php } ?>
                    <?php echo $editor_html; // 에디터 사용시는 에디터로, 아니면 textarea 로 노출 ?>
                    <?php if($write_min || $write_max) { ?>
                    <!-- 최소/최대 글자 수 사용 시 -->
                    <div id="char_count_wrap"><span id="char_count"></span>글자</div>
                    <?php } ?>
                </td>
            </tr>

            <?php for ($i=1; $is_link && $i<=G5_LINK_COUNT; $i++) { ?>
            <tr>
                <th scope="row"><label for="wr_link<?php echo $i ?>">링크 #<?php echo $i ?></label></th>
                <td><input type="url" name="wr_link<?php echo $i ?>" value="<?php if($w=="u"){echo$write['wr_link'.$i];} ?>" id="wr_link<?php echo $i ?>" class="frm_input wr_link"></td>
            </tr>
            <?php } ?>

            <?php for ($i=0; $is_file && $i<$file_count; $i++) { ?>
            <tr>
                <th scope="row">파일 #<?php echo $i+1 ?></th>
                <td>
                    <input type="file" name="bf_file[]" title="파일첨부 <?php echo $i+1 ?> :  용량 <?php echo $upload_max_filesize ?> 이하만 업로드 가능" class="frm_file frm_input">
                    <?php if ($is_file_content) { ?>
                    <input type="text" name="bf_content[]" value="<?php echo ($w == 'u') ? $file[$i]['bf_content'] : ''; ?>" title="파일 설명을 입력해주세요." class="frm_file frm_input">
                    <?php } ?>
                    <?php if($w == 'u' && $file[$i]['file']) { ?>
                    <input type="checkbox" id="bf_file_del<?php echo $i ?>" name="bf_file_del[<?php echo $i; ?>]" value="1"> <label for="bf_file_del<?php echo $i ?>"><?php echo $file[$i]['source'].'('.$file[$i]['size'].')'; ?> 파일 삭제</label>
                    <?php } ?>
                </td>
            </tr>
            <?php } ?>

            <?php if ($is_guest) { //자동등록방지 ?>
            <tr>
                <th scope="row">자동등록방지</th>
                <td>
                    <?php echo $captcha_html ?>
                </td>
            </tr>
            <?php } ?>

            </tbody>
            </table>
        </div>

        <div class="btn_confirm">
            <input type="submit" value="작성완료" id="btn_submit" class="btn_submit" accesskey="s">
            <?php if ($bo_table == 'request') { ?>
            <?php } else { ?>
            <a href="./board.php?bo_table=<?php echo $bo_table ?>" class="btn_cancel">뒤로가기</a>
            <?php } ?>
        </div>
        </form>
    </section>
</div>
<script>
<?php if($write_min || $write_max) { ?>
// 글자수 제한
var char_min = parseInt(<?php echo $write_min; ?>); // 최소
var char_max = parseInt(<?php echo $write_max; ?>); // 최대
check_byte("wr_content", "char_count");

$(function() {
    $("#wr_content").on("keyup", function() {
        check_byte("wr_content", "char_count");
    });
});

<?php } ?>
function html_auto_br(obj)
{
    if (obj.checked) {
        result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
        if (result)
            obj.value = "html2";
        else
            obj.value = "html1";
    }
    else
        obj.value = "";
}

function fwrite_submit(f)
{
    <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

    var subject = "";
    var content = "";
    $.ajax({
        url: g5_bbs_url+"/ajax.filter.php",
        type: "POST",
        data: {
            "subject": f.wr_subject.value,
            "content": f.wr_content.value
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data, textStatus) {
            subject = data.subject;
            content = data.content;
        }
    });

    if (subject) {
        alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
        f.wr_subject.focus();
        return false;
    }

    if (content) {
        alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
        if (typeof(ed_wr_content) != "undefined")
            ed_wr_content.returnFalse();
        else
            f.wr_content.focus();
        return false;
    }

    if (document.getElementById("char_count")) {
        if (char_min > 0 || char_max > 0) {
            var cnt = parseInt(check_byte("wr_content", "char_count"));
            if (char_min > 0 && char_min > cnt) {
                alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                return false;
            }
            else if (char_max > 0 && char_max < cnt) {
                alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                return false;
            }
        }
    }

     <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

    document.getElementById("btn_submit").disabled = "disabled";

    return true;
}
</script>
