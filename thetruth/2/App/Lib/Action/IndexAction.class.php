<?php
class IndexAction extends Action 
{
	
    public function index() {
        session_start();
	error_reporting(1);
		$this->display();
    }
	public function user_name()
	{
		session_start();
		error_reporting(1);
		
		$usrname = $_REQUEST['usr_name'];
		if(!$usrname){$this->error('系统查找不到该操作,请重试!');}      
		$modelcheck=M('user');
		
		$addb=$modelcheck->where("username='$usrname'")->find();
		echo $addb['username'];
		if(!$addb) 
		{
			$model=D("user");
			$mydata['username']=$usrname;
			if($mydata) 
			{
				$result	=$model->add($mydata);
				if($result)
				{
					$_SESSION['name']=$usrname;
					$this->success('成功登陆','top');
				}
				else{$this->error('登陆失败1');}
			}
			else
			{
				$this->error('登陆失败2');
			}
		}
		else
		{
			$_SESSION['name']=$addb['username'];
			$this->success('成功登陆','top');
		}
	}
	public function submit()
	{
		session_start();
		error_reporting(1);
		$text = $_REQUEST['thetruth'];
		if(!$text){$this->error('系统查找不到该操作,请重试!');}
		$model=D("info");
		$mydata['content']=$text;
		$mydata['author_userid']=$_SESSION['name'];
		$mydata['state']=0;
		$mydata['label']='你妹';
		$mydata['z']=0;
		$mydata['f']=0;
		if($mydata) 
		{
			$result	=$model->add($mydata);
			if($result)
			{
				$this->success('提交成功');
			}
			else{$this->error('提交失败');}
		}
		else
		{
			$this->error('提交失败');
		}
		
	}
	public function top()
	{
		session_start();
		error_reporting(1);
		$usrname = $_SESSION['name'];
		$model=M('info');
		$list=$model->select();
		if(!$usrname){$this->error('系统查找不到该操作,请重试!');}
		$this->assign('infolist',$list);
		$this->assign('usrname',$usrname);
		$this->display();
	}
	public function answerlist()
	{
		session_start();
		error_reporting(1);
		$id = $_REQUEST['id'];
		if(!$id){$this->error('系统查找不到该操作,请重试!');}
		$model=M("ans");
		$model2=M("info");
		$list=$model->where("info_id=$id")->select();
		$info=$model2->where("id=$id")->find();
		$this->assign('info',$info);
		$this->assign('anslist',$list);
		$this->display();
	}
	public function submitans()
	{
		session_start();
		error_reporting(1);
		$zof=$_POST['zof'];
		$text=$_POST['thetruth'];
		$id = $_REQUEST['id'];
		$user=$_SESSION['name'];
		if(!$zof){$this->error('系统查找不到该操作,请重试!');}
		$modelfind=M("ans");
		$done=$modelfind->where("info_id=$id and answer_id='$user'")->find();
		if($done)
		{
			$this->error('对这个提问你已经发表过见解');
		}
		else
		{
			$modelb=M("info");
			$model=D("ans");
			$mydata['answer_id']=$user;
			$mydata['content']=$text;
			$mydata['info_id']=$id;
			
			if($zof=='z')
			{
				$mydata['zorf']=1;
			}
			else
			{
				$mydata['zorf']=0;
			}
			if($mydata)
			{
				$result	=$model->add($mydata);
				if($result)
				{
					if($mydata['zorf'])
					{
						$modelb->where("id=$id")->setInc("z",1);
					}
					else
					{
						$modelb->where("id=$id")->setInc("f",1);
					}
					$this->success('提交成功');
				}
				else
				{
					$this->error('提交失败');
				}
			}
			else
			{
				$this->error('提交失败');
			}
		}
		
	}
	public function upanddown()
	{
		session_start();
		error_reporting(1);
		$id=$_REQUEST['ans_id'];
		$tag=$_REQUEST['tag'];
		$userid=$_SESSION['name'];
		if(!$id||!$userid||!$tag)
		{
			$this->error('系统查找不到该操作,请重试!');
		}
		$modelfind=M("upanddown");
		$done=$modelfind->where("ans_id=$id and user_id='$userid'")->find();
		if($done)
		{
			$this->error('对同一证据只能(顶/踩)一次');
		}
		$modelb=M("ans");
		$model=D("upanddown");
		$mydata['ans_id']=$id;
		$mydata['user_id']=$userid;
		if($mydata)
		{
			$result	=$model->add($mydata);
			if($result)
			{
				if($tag=='z')
				{
					$modelb->where("id=$id")->setInc("up",1);
				}
				else
				{
					$modelb->where("id=$id")->setInc("down",1);
				}
				$this->success('操作成功');
			}
			else
			{
				$this->error('操作成功');
			}
		}
		else
		{
			$this->error('操作成功');
		}
	}
	
	
}
?>

