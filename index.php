<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<title>Hg版本打包工具</title>
		<link rel="stylesheet" type="text/css" href="style.css">
		<script type="text/javascript" src="jquery-1.11.1.js"></script>
		<script type="text/javascript" src="app.js"></script>
	</head>
	<body>
		<?php
			include_once "common.php";
			app_init();
		?>
		<div class="content">
			<h3 style="color:#1b809e">Hg增量包自动生成工具 v2.07</h3>
			<div>
				<form action="" method="post">
					<div>
						<label for="name">当前项目目录:<?php echo $project_dir; ?></label>
						&nbsp;&nbsp;<Input type="submit" action="submit">&nbsp;&nbsp;<input id="refresh"type="button" value="返回">
					</div>
					<div>
						<br>
						<?php
							include "app.class.php";
							$app = new App;
							$app->run();
						?>
					</div>

					<h4>合并节点提取模式</h4>
					<div class="genMethod">
						<label for="upperm"><input id="upperm" class="extraction" type="radio" name="m_model" value="upperm" checked><span>大M</span></label>
						<label for="lowerm"><input id="lowerm" class="extraction" type="radio" name="m_model" value="lowerm" >小m</label>
					</div>

					<h4>提取方式</h4>
					<div class="genMethod">
						
						<label for="braches"><input id="braches" class="extraction" type="checkbox" name="extraction[]" value="branch" checked><span>使用分支</span></label>
						<label for="version"><input id="version" class="extraction" type="checkbox" name="extraction[]" value="version" checked>使用版本号</label>
						
					</div>
					<h4>选择分支</h4>
					<div class="branch_zone">
						
						<?php
						exec("hg branches",$branches);
						?>
						<ul>
							<?php foreach ($branches as $k => $v) {
								// $tmp_arr = split("[ ]+",$v);
								$branch_name = trim(substr($v,0,27));
								$version_info = trim(substr($v,28,18));
								$status = trim(substr($v,45,10));
								$v = str_replace(" ", "&nbsp;",$v);
								echo "
									<li>
										<input type='checkbox' class='branches' name='branch_name[]' value='{$branch_name}' />
										<label for='name' ><span class='branch_name'>{$v}</span></label>
									</li>
								";
							} ?>
						</ul>
					</div>
					<div  class="version_zone">
						<h4>版本号</h4>
						<input type="radio" name="version_model" value="single">单版本模式
						<input type="radio" name="version_model" value="multi" checked>多版本模式
						<ul class="model single_model">
							<li>
								<label for="radio-choice-1">版本号:</label>
								<input type="text" name="begin_ver"/>
							</li>
						</ul>
						<ul class="model multi_model">
							<li>
								<label for="radio-choice-1">起始版本号:</label>
								<input type="text" name="begin_ver"/>
							</li>
							<li>
								<label for="radio-choice-1">结束版本号:</label>
								<input type="text" name="end_ver"/>
							</li>
						</ul>
					</div>
					
				</form>
			</div>
		</div>
	</body>
</html>