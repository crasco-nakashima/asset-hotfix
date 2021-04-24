<?php $this->assign('keywords', $keywords); ?>
<?php $this->assign('description', $description); ?>
<?php $this->assign('title', $title); ?>
<?php $this->assign('thumnail', $thumnail); ?>
<?php $isLogged = !is_null($magazineMailList->email); ?>
<?php if ($isLogged) { ?> <h2 class="btmBorder">マイページ</h2> <?php };?>
<ul class="mypage clearfix">
	<?php if ($isLogged) { ?> <li><a href="/account">お客様情報</a></li> <?php };?>
	<?php if (!$isLogged) { ?> <li><a href="/registration">会員登録</a></li> <?php };?>
	<li class="active"><a href="/newsletter">メールマガジン</a></li>
	<?php if ($isLogged) { ?> <li><a href="/changepassword">パスワード変更</a></li> <?php };?>
	<?php if ($isLogged) { ?> <li><a href="/closeaccount">退会</a></li> <?php };?>
</ul>
<p class="form-instructions">購読されるメールマガジンにチェックを入れて購読するボタンを押してください。</p>
<p>　<?= $this->Flash->render() ?></p>
<?= $this->Form->create($magazineMailList, ['id' => 'newsletterForm']) ?>
<input type="hidden" name="magazine_mail" value="">
<ul class="form-listWide clearfix">
	<li>
		<label for="email" class="required"><em>*</em>受信メールアドレス</label>
		<div class="input-box">
			<?php
			$disabled = $isLogged ? 'disabled' : '';
			echo $this->Form->input('email', ['class'=> 'input-text required-entry',
			'title' => 'メールアドレス', 'label' => false, 'type' => 'email', $disabled]); ?>
		</div>
	</li>
	<li>
		<input name="magazine_mail[]" class="" id="newsletter01" type="checkbox" value="1" <?php
		echo strpos($magazineMailList->magazine_mail, '1') !== false ? 'checked' : '';
		?>>
		<label for="newsletter01">クラスコの資産運用</label>
	</li>
	<li>
		<input name="magazine_mail[]" class="" id="newsletter02" type="checkbox" value="2" <?php
		echo strpos($magazineMailList->magazine_mail, '2') !== false ? 'checked' : '';
		?>>
		<label for="newsletter02">クラスコの不動産投資</label>
	</li>
	<li>
		<input name="magazine_mail[]" class="" id="newsletter03" type="checkbox" value="3" <?php
		echo strpos($magazineMailList->magazine_mail, '3') !== false ? 'checked' : '';
		?>>
		<label for="newsletter03">クラスコの賃貸経営</label>
	</li>
	<li style="display:<?= $isLogged ? 'none' : ''?>;">
		<label for="name" class="">お名前</label>
		<div class="input-box">
			<?php echo $this->Form->input('name', ['class'=> 'input-text required-entry',
			'title' => 'お名前', 'label' => false]); ?>
		</div>
	</li>
	<li style="display:<?= $isLogged ? 'none' : ''?>;">
		<label for="furigana" class="">フリガナ</label>
		<div class="input-box">
			<?php echo $this->Form->input('furigana', ['class'=> 'input-text required-entry',
			'title' => 'フリガナ', 'label' => false]); ?>
		</div>
	</li>
	<li style="display:<?= $isLogged ? 'none' : ''?>;">
		<label for="zip1" class="">郵便番号</label>
		<div class="input-box">
			<?php echo $this->Form->text('zip1', ['class'=> 'input-text required-entry w100',
			'type' => 'name', 'title' => '郵便番号', 'label' => false]); ?>
			- <?php echo $this->Form->text('zip2', ['class'=> 'input-text required-entry w100',
			'type' => 'name', 'title' => '郵便番号', 'label' => false, 'id' => 'divzip']); ?>
		</div>
	</li>
	<li style="display:<?= $isLogged ? 'none' : ''?>;">
		<label for="pref_id" class="">住所</label>
		<div class="input-box">
			<?php echo $this->Form->select('pref_id', $pref, []); ?>
			<?php echo $this->Form->text('district', ['class'=> 'input-text required-entry',
			'title' => '市区町村', 'placeholder' => '市区町村']); ?>
			<?php echo $this->Form->text('sub_address', ['class'=> 'input-text required-entry',
			'title' => '以下住所', 'placeholder' => '以下住所']); ?>
			<?php echo $this->Form->text('mansion', ['class'=> 'input-text required-entry',
			'title' => 'マンション名', 'placeholder' => 'マンション名']); ?>
		</div>
	</li>
	<li style="display:<?= $isLogged ? 'none' : ''?>;">
		<label for="phone_number" class="">お電話番号（ハイフン無し　例：0333330000</label>
		<div class="input-box">
			<?php echo $this->Form->text('phone_number', ['class'=> 'input-text required-entry',
			'title' => 'お電話番号', 'type' => 'number']); ?>
		</div>
	</li>
	<li>
		<div class="g-recaptcha" data-callback="syncerRecaptchaCallback" data-sitekey="6Le-z6saAAAAAGd8AZ1Po-XwxgpZSiz_46H8HRVc"></div>
	</li>
</ul>
<div align="center">
	<br>
	<button name="create_action" title="購読する" class="orangeBtn" type="submit" disabled="disabled" id="js-submit-button">購読する</button>
	<button name="taikai_action" title="購読を解除する" class="orangeBtn" type="submit" disabled="disabled" id="js-unscribe-button"
	onclick="return confirm('メールマガジン退会しますか？');">購読を解除する</button>
</div>
<?= $this->Form->end() ?>

<script>
function syncerRecaptchaCallback( code )
{
	if(code != "")
	{
		document.getElementById("js-submit-button").removeAttribute("disabled");
		document.getElementById("js-unscribe-button").removeAttribute("disabled");
	}
}
</script>
