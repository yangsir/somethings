<?php
/**
 * 微官网模块微站定义
 *
 * @author WeEngine Team
 * @url http://www.we7.cc
 */
defined('IN_IA') or exit('Access Denied');
include_once IA_ROOT . '/source/modules/site/model.php';
class SiteModuleSite extends WeModuleSite {

	public function doWebCategory() {
		global $_W, $_GPC;
		$foo = !empty($_GPC['foo']) ? $_GPC['foo'] : 'display';
		if ($foo == 'display') {
			if (!empty($_GPC['displayorder'])) {
				foreach ($_GPC['displayorder'] as $id => $displayorder) {
					pdo_update('article_category', array('displayorder' => $displayorder), array('id' => $id));
				}
				message('分类排序更新成功！', 'refresh', 'success');
			}
			$children = array();
			$category = pdo_fetchall("SELECT * FROM ".tablename('article_category')." WHERE weid = '{$_W['weid']}' ORDER BY displayorder ASC");
			foreach ($category as $index => $row) {
				if (!empty($row['parentid'])){
					$children[$row['parentid']][] = $row;
					unset($category[$index]);
				}
			}
			include $this->template('category');
		} elseif ($foo == 'post') {
			$parentid = intval($_GPC['parentid']);
			$id = intval($_GPC['id']);
			if(!empty($id)) {
				$category = pdo_fetch("SELECT * FROM ".tablename('article_category')." WHERE id = '$id'");
				if (!empty($category['nid'])) {
					$nav = pdo_fetch("SELECT * FROM ".tablename('site_nav')." WHERE id = :id" , array(':id' => $category['nid']));
					$nav['css'] = unserialize($nav['css']);
					if (strexists($nav['icon'], 'images/')) {
						$nav['fileicon'] = $nav['icon'];
						$nav['icon'] = '';
					}
				}
			} else {
				$category = array(
					'displayorder' => 0,
				);
			}
			if (!empty($parentid)) {
				$parent = pdo_fetch("SELECT id, name FROM ".tablename('article_category')." WHERE id = '$parentid'");
				if (empty($parent)) {
					message('抱歉，上级分类不存在或是已经被删除！', $this->createWebUrl('category', array('foo' => 'display')), 'error');
				}
			}
			if (checksubmit('fileupload-delete')) {
				file_delete($_GPC['fileupload-delete']);
				pdo_update('site_nav', array('icon' => ''), array('id' => $category['nid']));
				message('删除成功！', referer(), 'success');
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['cname'])) {
					message('抱歉，请输入分类名称！');
				}
				$data = array(
					'weid' => $_W['weid'],
					'name' => $_GPC['cname'],
					'displayorder' => intval($_GPC['displayorder']),
					'parentid' => intval($parentid),
					'description' => $_GPC['description'],
'date' => time(),
				);
				if (!empty($id)) {
					unset($data['parentid']);
					pdo_update('article_category', $data, array('id' => $id));
				} else {
					pdo_insert('article_category', $data);
					$id = pdo_insertid();
				}
				if (!empty($_GPC['isnav'])) {
					$nav = array(
						'weid' => $_W['weid'],
						'name' => $data['name'],
						'displayorder' => 0,
						'position' => 1,
						'url' => create_url('mobile/channel', array('name' => 'list', 'cid' => $id)),
						'issystem' => 0,
						'status' => 1,
					);
					$nav['css'] = serialize(array(
						'icon' => array(
							'font-size' => $_GPC['icon']['size'],
							'color' => $_GPC['icon']['color'],
							'width' => $_GPC['icon']['size'],
							'icon' => $_GPC['icon']['icon'],
						),
					));
					if (!empty($_FILES['icon']['tmp_name'])) {
						file_delete($_GPC['icon_old']);
						$upload = file_upload($_FILES['icon']);
						if (is_error($upload)) {
							message($upload['message'], '', 'error');
						}
						$nav['icon'] = $upload['path'];
					}
					if (empty($category['nid'])) {
						pdo_insert('site_nav', $nav);
						pdo_update('article_category', array('nid' => pdo_insertid()), array('id' => $id));
					} else {
						pdo_update('site_nav', $nav, array('id' => $category['nid']));
					}
				} else {
					pdo_delete('site_nav', array('id' => $category['nid']));
					pdo_update('article_category', array('nid' => 0), array('id' => $id));
				}
				message('更新分类成功！', $this->createWebUrl('category'), 'success');
			}
			include $this->template('category');
		} elseif ($foo == 'fetch') {
			$category = pdo_fetchall("SELECT id, name FROM ".tablename('article_category')." WHERE parentid = '".intval($_GPC['parentid'])."' ORDER BY id ASC");
			message($category, '', 'ajax');
		} elseif ($foo == 'delete') {
			$id = intval($_GPC['id']);
			$category = pdo_fetch("SELECT id, parentid, nid FROM ".tablename('article_category')." WHERE id = '$id'");
			if (empty($category)) {
				message('抱歉，分类不存在或是已经被删除！', $this->createWebUrl('category'), 'error');
			}
			$navs = pdo_fetchall("SELECT icon, id FROM ".tablename('site_nav')." WHERE id IN (SELECT nid FROM ".tablename('article_category')." WHERE id = {$id} OR parentid = '$id')", array(), 'id');
			if (!empty($navs)) {
				foreach ($navs as $row) {
					file_delete($row['icon']);
				}
				pdo_query("DELETE FROM ".tablename('site_nav')." WHERE id IN (".implode(',', array_keys($navs)).")");
			}
			pdo_delete('article_category', array('id' => $id, 'parentid' => $id), 'OR');
			message('分类删除成功！', $this->createWebUrl('category'), 'success');
		}
	}

	public function doWebArticle() {
		global $_W, $_GPC;
		$foo = !empty($_GPC['foo']) ? $_GPC['foo'] : 'display';
		$category = pdo_fetchall("SELECT * FROM ".tablename('article_category')." WHERE weid = '{$_W['weid']}' ORDER BY parentid ASC");
		if (!empty($category)) {
			$children = '';
			foreach ($category as $cid => $cate) {
				if (!empty($cate['parentid'])) {
					$children[$cate['parentid']][] = array($cate['id'], $cate['name']);
				}
			}
		}
		if ($foo == 'display') {
			$pindex = max(1, intval($_GPC['page']));
			$psize = 20;
			$condition = '';
			$params = array();
			if (!empty($_GPC['keyword'])) {
				$condition .= " AND title LIKE :keyword";
				$params[':keyword'] = "%{$_GPC['keyword']}%";
			}

			if (!empty($_GPC['cate_2'])) {
				$cid = intval($_GPC['cate_2']);
				$condition .= " AND ccate = '{$cid}'";
			} elseif (!empty($_GPC['cate_1'])) {
				$cid = intval($_GPC['cate_1']);
				$condition .= " AND pcate = '{$cid}'";
			}

			$list = pdo_fetchall("SELECT * FROM ".tablename('article')." WHERE weid = '{$_W['weid']}' $condition ORDER BY id DESC LIMIT ".($pindex - 1) * $psize.','.$psize, $params);
			$total = pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('article') . " WHERE weid = '{$_W['weid']}'");
			$pager = pagination($total, $pindex, $psize);

			include $this->template('article');
		} elseif ($foo == 'post') {
			$id = intval($_GPC['id']);
			if (!empty($id)) {
				$item = pdo_fetch("SELECT * FROM ".tablename('article')." WHERE id = :id" , array(':id' => $id));
				$item['type'] = explode(',', $item['type']);
				if (empty($item)) {
					message('抱歉，文章不存在或是已经删除！', '', 'error');
				}
			}
			if (checksubmit('fileupload-delete')) {
				file_delete($_GPC['fileupload-delete']);
				pdo_update('article', array('thumb' => ''), array('id' => $id));
				message('删除成功！', referer(), 'success');
			}
			if (checksubmit('submit')) {
				if (empty($_GPC['title'])) {
					message('标题不能为空，请输入标题！');
				}
				$data = array(
					'weid' => $_W['weid'],
					'type' => @implode(',', $_GPC['option']).',',
					'pcate' => intval($_GPC['cate_1']),
					'ccate' => intval($_GPC['cate_2']),
					'title' => $_GPC['title'],
					'content' => htmlspecialchars_decode($_GPC['content']),
					'source' => $_GPC['source'],
					'author' => $_GPC['author'],
					'createtime' => TIMESTAMP,
				);
				if (!empty($_FILES['thumb']['tmp_name'])) {
					file_delete($_GPC['thumb_old']);
					$upload = file_upload($_FILES['thumb']);
					if (is_error($upload)) {
						message($upload['message'], '', 'error');
					}
					$data['thumb'] = $upload['path'];
				} elseif (!empty($_GPC['autolitpic'])) {
					$match = array();
					preg_match('/attachment\/(.*?)"/', $_GPC['content'], $match);
					if (!empty($match[1])) {
						$data['thumb'] = $match[1];
					}
				}
				if (empty($id)) {
					pdo_insert('article', $data);
				} else {
					unset($data['createtime']);
					pdo_update('article', $data, array('id' => $id));
				}
				message('文章更新成功！', $this->createWebUrl('article', array('foo' => 'display')), 'success');
			} else {
				include $this->template('article');
			}
		} elseif ($foo == 'delete') {
			$id = intval($_GPC['id']);
			$row = pdo_fetch("SELECT id, thumb FROM ".tablename('article')." WHERE id = :id", array(':id' => $id));
			if (empty($row)) {
				message('抱歉，文章不存在或是已经被删除！');
			}
			if (!empty($row['thumb'])) {
				file_delete($row['thumb']);
			}
			pdo_delete('article', array('id' => $id));
			message('删除成功！', referer(), 'success');
		}
	}

	public function doWebQuery() {
		global $_W, $_GPC;
		$kwd = $_GPC['keyword'];
		$sql = 'SELECT * FROM ' . tablename('article') . ' WHERE `weid`=:weid AND `title` LIKE :title';
		$params = array();
		$params[':weid'] = $_W['weid'];
		$params[':title'] = "%{$kwd}%";
		$ds = pdo_fetchall($sql, $params);
		foreach($ds as &$row) {
			$r = array();
			$r['id'] = $row['id'];
			$r['title'] = $row['title'];
			$r['description'] = cutstr(strip_tags($row['content']), 100);
			$r['thumb'] = $row['thumb'];
			$row['entry'] = $r;
		}
		include $this->template('query');
	}

	public function doWebDelete() {
		global $_W, $_GPC;
		$id = intval($_GPC['id']);
		pdo_delete('album_reply', array('id' => $id));
		message('删除成功！', referer(), 'success');
	}

	public function doMobileList() {
		global $_GPC, $_W;
		$cid = intval($_GPC['cid']);
		$category = pdo_fetchall("SELECT * FROM ".tablename('article_category')." WHERE parentid = '{$_GPC['pid']}' order by displayorder asc");
		if (empty($category)) {
			message('分类不存在或是已经被删除！',referer(),'tips');
		}
		$title = $category['name'];
  
		include $this->template('list');
	}

 public function doMobileList1() {
		global $_GPC, $_W;
             	$pindex = max(1, intval($_GPC['page']));
                $psize=6;
	        $result = array();
	        $collec = pdo_fetchall('SELECT * FROM ' . tablename('collection') . " WHERE weid = '{$_W['weid']}' and who='{$_W['fans']['from_user']}'"." LIMIT " . ($pindex - 1) * $psize .',' .$psize);
	       	$n=pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('collection') . " WHERE weid = '{$_W['weid']}' and who='{$_W['fans']['from_user']}'");
                $result['pager'] = paginationM($n, $pindex, $psize);	

	       
                   
                 
                		include $this->template('list1');
	}

 public function doMobileList2() {
		global $_GPC, $_W;
                $pindex = max(1, intval($_GPC['page']));
               $cid=$_GPC['cid'];
                $psize=6;
 $result = array();
	        $result['list'] = pdo_fetchall('SELECT * FROM ap_forms' ." LIMIT " . ($pindex - 1) * $psize .',' .$psize);
	       	$n=pdo_fetchcolumn('SELECT COUNT(*) FROM ap_forms');
                $result['pager'] = paginationM($n, $pindex, $psize);	
	       
                include $this->template('list2');
	}

 public function doMobileList3() {
		global $_GPC, $_W;
               $id = intval($_GPC['id']);
		$sql = "SELECT * FROM " . tablename('article') . " WHERE `id`=:id";
		$detail = pdo_fetch($sql, array(':id'=>$id));
		$detail = istripslashes($detail);
               $cid=$_GPC['cid'];
                include $this->template('list3');
	}


 public function doMobileList4() {
		global $_GPC, $_W;
                $pindex = max(1, intval($_GPC['page']));
                $psize=6;
 $result = array();
	        $result['list'] = pdo_fetchall('SELECT * FROM ' . tablename('course') . " WHERE weid = '{$_W['weid']}' and fname='{$_W['fans']['from_user']}'"." LIMIT " . ($pindex - 1) * $psize .',' .$psize);
	       	$n=pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('course') . " WHERE weid = '{$_W['weid']}' and fname='{$_W['fans']['from_user']}'");
                $result['pager'] = paginationM($n, $pindex, $psize);	
	       
                include $this->template('list4');
	}

public function doMobileList5() {
		global $_GPC, $_W;
		$cid = intval($_GPC['cid']);
		include $this->template('list5');
	}

	public function doMobileDetail() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$sql = "SELECT * FROM " . tablename('article') . " WHERE `id`=:id";
		$detail = pdo_fetch($sql, array(':id'=>$id));
		$detail = istripslashes($detail);
		$detail['thumb'] = $_W['attachurl'] . trim($detail['thumb'], '/');
		$title = $detail['title'];
		include $this->template('detail');
	}
public function doMobileDetail1() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$sql = "SELECT * FROM " . tablename('article') . " WHERE `id`=:id";
		$detail = pdo_fetch($sql, array(':id'=>$id));
		$detail = istripslashes($detail);
		$detail['thumb'] = $_W['attachurl'] . trim($detail['thumb'], '/');
		$title = $detail['title'];
		include $this->template('detail');
	}

             public function doMobileDetail2() {
		global $_GPC, $_W;
                $asql = "SELECT * FROM " . tablename('article') . " WHERE `ccate`=:id   order by createtime desc";
          $prize= pdo_fetch($asql, array(':id'=>'23'));
		$sql = "SELECT * FROM " . tablename('course') . " WHERE `weid`=:id and `fname`=:fname limit 0,3";
		$detail = pdo_fetchall($sql, array(':id'=>$_W['weid'],':fname'=>$_W['fans']['from_user']));
		$detail = istripslashes($detail);
                $sql = "SELECT * FROM " . tablename('fans') . " WHERE `id`=:who and `weid`=:weid";
		$p = pdo_fetch($sql, array(':who'=>$_W['fans']['id'],':weid'=>$_W['weid']));

                include $this->template('detail2');
	}

  public function doMobileDetail4() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$sql = "SELECT * FROM " . tablename('article') . " WHERE `id`=:id";
                
		$detail = pdo_fetch($sql, array(':id'=>$id));
		$detail = istripslashes($detail);
               $isread=intval($detail['isread'])+1;
		$detail['thumb'] = $_W['attachurl'] . trim($detail['thumb'], '/');
		$title = $detail['title']; pdo_update('article',array('isread'=>$isread),array('id'=>$id));
 $cstr = "SELECT * FROM " . tablename('course') . " WHERE `aid`=:id and `weid`=:weid and `fname`=:fname";

$fstr = "SELECT * FROM " . tablename('fans') . " WHERE  `weid`=:weid and `from_user`=:who";
		$course = pdo_fetch($cstr, array(':id'=>$id,':weid'=>$_W['weid'],':fname'=>$_W['fans']['from_user']));
if(!$course)
{
pdo_insert("course",array('title'=>$title,'credit'=>'5','weid'=>$_W['weid'],'fname'=>$_W['fans']['from_user'],'description'=>$detail['source'],'icon'=>$detail['thumb'],'date'=>time(),'aid'=>$id,'link'=>$this->createMobileUrl('detail4', array('name' => 'detail4', 'id' => $detail['id'], 'weid' => $_W['weid']))));
$fans = pdo_fetch($fstr, array(':weid'=>$_W['weid'],':who'=>$_W['fans']['from_user']));
$crd=intval($fans['credit1'])+5;
pdo_update("fans",array("credit1"=>$crd),array("weid"=>$_W['weid'],'from_user'=>$_W['fans']['from_user']));
}                         		include $this->template('detail4');
	}

 public function doMobileDetail5() {
		global $_GPC, $_W;
		$id = intval($_GPC['id']);
		$sql = "SELECT * FROM " . tablename('article') . " WHERE `id`=:id";
		$detail = pdo_fetch($sql, array(':id'=>$id));
		$detail = istripslashes($detail);
                 $isread=intval($detail['isread'])+1;
		$detail['thumb'] = $_W['attachurl'] . trim($detail['thumb'], '/');
                
		$title = $detail['title'];
         $sqlstr = "SELECT * FROM " . tablename('activity') . " WHERE `aid`=:id and `weid`=:weid and `who`=:who";     $rid=pdo_fetch($sqlstr,array(':id'=>$id,':weid'=>$_W['weid'],':who'=>$_W['fans']['from_user']));
pdo_update('article',array('isread'=>$isread),array('id'=>$id));

                                               		include $this->template('detail5');
	}
 public function doMobileDetail6() {
		global $_GPC, $_W;
             	$pindex = max(1, intval($_GPC['page']));
                $psize=6;
	        $result = array();
	        $result['list'] = pdo_fetchall('SELECT * FROM ' . tablename('activity') . " WHERE weid = '{$_W['weid']}' and who='{$_W['fans']['from_user']}'"." LIMIT " . ($pindex - 1) * $psize .',' .$psize);
	       	$n=pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('activity') . " WHERE weid = '{$_W['weid']}' and who='{$_W['fans']['from_user']}'");
	
                $t=pdo_fetchcolumn('SELECT COUNT(*) FROM ' . tablename('article') . " WHERE weid = '{$_W['weid']}' and pcate=59");
                $result['pager'] = paginationM($n, $pindex, $psize);	


                                               		include $this->template('detail6');
	}

	public function getCategoryTiles() {
		global $_W;
		$category = pdo_fetchall("SELECT id, name FROM ".tablename('article_category')." WHERE enabled = '1' AND weid = '{$_W['weid']}'");
		if (!empty($category)) {
			foreach ($category as $row) {
				$urls[] = array('title' => $row['name'], 'url' => $this->createMobileUrl('list', array('cid' => $row['id'])));
			}
			return $urls;
		}
	}
}