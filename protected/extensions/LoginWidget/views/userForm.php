<?php if(Yii::app()->user->role=='dealer'): ?>
<ul class="nav nav-pills nav-stacked"> 
	<li class="">
		<a href="/index.php/phones/default/create">Обновление номеров</a>
	</li>
	
	<li class="">
		<a href="/index.php/phones/">Все номера</a>
	</li>
	
	<li class="">
		<a href="/index.php/site/logout" >Выйти</a>	
	</li>
	
	
</ul>
<?php elseif(Yii::app()->user->role=='admin'):?>
<ul class="nav nav-pills nav-stacked"> 
	<li class="">
		<a href="/index.php/admin">Админ панель</a>
	</li>
	<li class="">
		<a href="/index.php/site/logout" >Выйти</a>	
	</li>
	
	
</ul>
<?php else:?>
<ul class="nav nav-pills nav-stacked"> 
	
	<li class="">
		<a href="/index.php/site/logout" >Выйти</a>	
	</li>
	
	
</ul>
<?php endif;?>