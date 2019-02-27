<?php
$sub_menu = '400200';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '분류순서';
include_once(G5_PATH.'/head.sub.php');
//add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/w3.css?ver='.G5_CSS_VER.'">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_CSS_URL.'/spinkit.css?ver='.G5_CSS_VER.'">', 0);
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL.'/css/admin.css?ver='.G5_CSS_VER.'">', 4); // 수정 불가
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL.'/css/patch.css?ver='.G5_CSS_VER.'">', 4); // 수정 불가
add_stylesheet('<link rel="stylesheet" href="'.G5_ADMIN_URL.'/css/style.css?ver='.G5_CSS_VER.'">', 4); // 커스텀은 이 파일에서

add_stylesheet('<link rel="stylesheet" href="'.G5_PLUGIN_URL.'/jquery-ui-1.12.1/jquery-ui.min.css?ver='.G5_CSS_VER.'" />', 1);
add_javascript('<script src="'.G5_PLUGIN_URL.'/jquery-ui-1.12.1/jquery-ui.min.js?ver='.G5_JS_VER.'"></script>', 1);
add_stylesheet('<link rel="stylesheet" href="'.G5_PLUGIN_URL.'/zTree_v3/css/zTreeStyle/zTreeStyle.custom.css?ver='.G5_CSS_VER.'" />', 1);
add_javascript('<script src="'.G5_PLUGIN_URL.'/zTree_v3/js/jquery.ztree.all.min.js?ver='.G5_JS_VER.'"></script>', 1);

// Category Depth
$ctg = sql_fetch("SELECT MAX(CAST(LENGTH(ca_id)/2 AS SIGNED)) AS depth FROM {$g5['g5_shop_category_table']}");
?>

<div id="categoryRoll" class="new_win scp_new_win">
    <h1><?php echo $g5['title']; ?></h1>

    <div class="new_win_con">

        <div id="categoryTree" class="categoryTree">
            <div id="categoryDepth" class="categoryDepth">
                <button button="button" class="categoryDepthBtn" data-tree="0">펼치기</button>
                <?php
                    for ($i=1; $i<=$ctg['depth']; $i++) {
                        echo '<button button="button" class="categoryDepthBtn" data-tree="'.$i.'">'.$i.'단계</button>';
                    }
                    echo PHP_EOL;
                ?>
                <button button="button" class="categoryDepthBtn" id="categoryRollSubmit">저장</button>
            </div>
            <div id="categoryOrder" class="categoryOrder category-tree ztree"></div>
        </div>

        <div id="categoryHold" class="holder">
            <div class="lockup"></div>
            <div class="loader">
                <div class="sk-wave">
                    <div class="sk-rect sk-rect1"></div>
                    <div class="sk-rect sk-rect2"></div>
                    <div class="sk-rect sk-rect3"></div>
                    <div class="sk-rect sk-rect4"></div>
                    <div class="sk-rect sk-rect5"></div>
                </div>
            </div>
        </div>

    </div>
</div>

<script src="<?php echo G5_ADMIN_URL ?>/admin.js?ver=<?php echo G5_JS_VER; ?>"></script>
<script>
var setting = {
    data: {
        simpleData: {
            enable: true
        }
    },
    edit: {
        enable: true,
        showRemoveBtn: false,
        showRenameBtn: false,
        drag: {
            prev: dropPrev,
            next: dropNext,
            inner: false
        }
    },
    check: {
        enable: false
    },
    view: {
        selectedMulti: false,
        dblClickExpand: dblClickExpand
    },
    callback: {
        beforeClick: beforeClick,
        beforeCollapse: beforeCollapse,
        beforeExpand: beforeExpand,
        onCollapse: onCollapse,
        onExpand: onExpand,
        beforeDrag: beforeDrag,
        beforeDrop: beforeDrop,
        onClick: categoryLoader,
        onCheck: categoryPublic,
        onDrop:  categoryLineup,
    }
};

var zNodes =[<?php
    // Category Nodes
    $foo = "select ca_id,ca_name,ca_order from {$g5['g5_shop_category_table']} where (1) order by ca_order,ca_id ";
    $bar = sql_query($foo);
    for ($i=0; $tmp=sql_fetch_array($bar); $i++) {
        echo '{id:"'.$tmp['ca_id'].'",pId:"'.substr($tmp['ca_id'],0,-2).'",name:"'.$tmp['ca_name'].'"},';
    }
?>];

$(function() {
    $.fn.zTree.init($("#categoryOrder"), setting, zNodes);

    $('[data-tree]').on('click', function() {
        var $this = $(this),
            $tree = $.fn.zTree.getZTreeObj("categoryOrder"),
            _tree = $this.data('tree');

        if (_tree == 0) {
            $tree.expandAll(true);
        } else if (_tree == 1) {
            $tree.expandAll(false);
        } else {
            $tree.expandAll(false);
            for (i=0; i<_tree-1; i++) {
                $('.switch.level'+i).trigger('click');
            }
        }
    }).filter('[data-tree="2"]').trigger('click');

    $('#categoryRollSubmit').on('click', function() {
        var $this = $(this),
            $tree = $.fn.zTree.getZTreeObj("categoryOrder"),
            nodes = $tree.getNodes(),
            array = $tree.transformToArray(nodes);

        if (confirm("분류순서를 저장하시겠습니까?")) {
            $('#categoryHold').show();
            $this.prop('disabled', true);
            $.ajax({
                type: "POST",
                data: {
                    nodes: JSON.stringify(array), 
                    token: get_ajax_token()
                },
                url: 'categoryrollupdate.php',
                success: function(data) {
                    alert("저장했습니다.");
                    opener.location.reload(true);
                },
                error : function(xhr, status, error) {
                    alert(error);
                    location.reload(true);
                },
                complete : function(xhr, status) {
                    $('#categoryHold').hide();
                    $this.prop('disabled', false);
                }
            });
        }

        return false;
    });
});

// When the user scrolls the page
window.onscroll = function() {myFunction()};
var header = document.getElementById("categoryDepth");
var sticky = header.offsetTop;
function myFunction() {
    if (window.pageYOffset >= sticky) {
        header.classList.add("sticky");
    } else {
        header.classList.remove("sticky");
    }
}

function filter(treeId, parentNode, childNodes) {
    if (!childNodes) return null;
    for (var i=0, l=childNodes.length; i<l; i++) {
        childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
    }
    return childNodes;
}

function dropPrev(treeId, nodes, targetNode) {
    var pNode = targetNode.getParentNode();
    if (pNode && pNode.dropInner === false) {
        return false;
    } else {
        for (var i=0,l=curDragNodes.length; i<l; i++) {
            var curPNode = curDragNodes[i].getParentNode();
            if (curPNode !== targetNode.getParentNode()) {
                return false;
            }
        }
    }
    return true;
}

function dropNext(treeId, nodes, targetNode) {
    var pNode = targetNode.getParentNode();
    if (pNode && pNode.dropInner === false) {
        return false;
    } else {
        for (var i=0,l=curDragNodes.length; i<l; i++) {
            var curPNode = curDragNodes[i].getParentNode();
            if (curPNode !== targetNode.getParentNode()) {
                return false;
            }
        }
    }
    return true;
}

var curDragNodes, autoExpandNode;
function beforeDrag(treeId, treeNodes) {
    for (var i=0,l=treeNodes.length; i<l; i++) {
        if (treeNodes[i].drag === false) {
            curDragNodes = null;
            return false;
        } else if (treeNodes[i].parentTId && treeNodes[i].getParentNode().childDrag === false) {
            curDragNodes = null;
            return false;
        }
    }
    curDragNodes = treeNodes || new Array();
    return true;
}

function beforeDrop(treeId, treeNodes, targetNode, moveType, isCopy) {
}

function onDrag(event, treeId, treeNodes) {
}

function onDrop(event, treeId, treeNodes, targetNode, moveType, isCopy) {
}

function beforeClick(treeId, treeNode) {
}

function beforeCollapse(treeId, treeNode) {
    return (treeNode.collapse !== false);
}

function onCollapse(event, treeId, treeNode) {
}

function beforeExpand(treeId, treeNode) {
    return (treeNode.expand !== false);
}

function onExpand(event, treeId, treeNode) {
}

function dblClickExpand(treeId, treeNode) {
    return true;
    return treeNode.level > 0;
}

function categoryLoader(event, treeId, treeNode) {
}

function categoryPublic(event, treeId, treeNode) {
}

function categoryLineup(event, treeId, treeNodes, targetNode, moveType, isCopy) {
}

function categoryUpdate(obj) {
}
</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>