<?php $this->assign('keywords', $keywords); ?>
<?php $this->assign('description', $description); ?>
<?php $this->assign('title', $title); ?>
<?php $this->assign('thumnail', $thumnail); ?>
<ul class="mypage clearfix">
	<li class="active"><a href="/registration">会員登録</a></li>
	<li><a href="/newsletter">メールマガジン</a></li>
</ul>
<h2 class="btmBorder">会員登録</h2>
<div class="lower_contents_inner">
	<p class="form-instructions"><span class="required">* 必須項目</span></p>
	<p>　<?= $this->Flash->render() ?></p>
	<?= $this->Form->create($user, ['id' => 'registrationForm']) ?>
	<ul class="form-listWide clearfix">
		<li>
			<label for="email" class="required"><em>*</em>メールアドレス</label>
			<div class="input-box">
				<input name="mail" type="email" class="input-text required-entry" title="メールアドレス">
			</div>
		</li>
		<li>
			<label class="required"><em>*</em>パスワード（8文字以上：半角英数字）</label>
			<div class="input-box">
				<input id="password" name="password" type="password" class="input-text required-entry" title="パスワード">
			</div>
		</li>
		<li>
			<label class="required"><em>*</em>パスワード確認の為もう一度ご入力ください。</label>
			<div class="input-box">
				<input name="password2" type="password" class="input-text required-entry" title="パスワード">
			</div>
		</li>
		<li>
			<label for="name" class="">お名前</label>
			<div class="input-box">
				<?php echo $this->Form->input('name', [
					'class' => 'input-text required-entry',
					'title' => 'お名前', 'label' => false
				]); ?>
			</div>
		</li>
		<li>
			<label for="furigana" class="">フリガナ</label>
			<div class="input-box">
				<?php echo $this->Form->input('furigana', [
					'class' => 'input-text required-entry',
					'title' => 'フリガナ', 'label' => false
				]); ?>
			</div>
		</li>
		<li>
			<label for="zip1" class="">郵便番号</label>
			<div class="input-box">
				<?php echo $this->Form->text('zip1', [
					'class' => 'input-text required-entry w100',
					'type' => 'name', 'title' => '郵便番号', 'label' => false
				]); ?>
				- <?php echo $this->Form->text('zip2', [
						'class' => 'input-text required-entry w100',
						'type' => 'name', 'title' => '郵便番号', 'label' => false, 'id' => 'divzip'
					]); ?>
			</div>
		</li>
		<li>
			<label for="pref_id" class="">住所</label>
			<div class="input-box input_style">
				<?php echo $this->Form->select('pref_id', $pref, []); ?>
				<?php echo $this->Form->text('district', [
					'class' => 'input-text required-entry',
					'title' => '市区町村', 'placeholder' => '市区町村'
				]); ?>
				<?php echo $this->Form->text('sub_address', [
					'class' => 'input-text required-entry',
					'title' => '以下住所', 'placeholder' => '以下住所'
				]); ?>
				<?php echo $this->Form->text('mansion', [
					'class' => 'input-text required-entry',
					'title' => 'マンション名', 'placeholder' => 'マンション名'
				]); ?>
			</div>
		</li>
		<li>
			<label for="phone_number" class="">お電話番号（ハイフン無し　例：0333330000</label>
			<div class="input-box">
				<?php echo $this->Form->text('phone_number', [
					'class' => 'input-text required-entry',
					'title' => 'お電話番号', 'type' => 'number'
				]); ?>
			</div>
		</li>
		<li>
			<label>メールマガジン登録</label>
			<input type="hidden" name="magazine_mail" value="">
			<div class="magazine"><input type="checkbox" value="1" name="magazine_mail[]">クラスコの資産運用</div>
			<div class="magazine"><input type="checkbox" value="2" name="magazine_mail[]">クラスコの不動産投資</div>
			<div class="magazine"><input type="checkbox" value="3" name="magazine_mail[]">クラスコの賃貸経営</div>
		</li>
		<li>
			<label class="required"><em>*</em>プライバシーポリシー</label>
			<div class="magazine" id="label-agree"><input type="checkbox" value="1" name="agree">同意する</div>
		</li>
		<li>
			<div class="g-recaptcha" data-callback="syncerRecaptchaCallback" data-sitekey=<?= GOOGLE_RECAPTHA_SITE_KEY ?>></div>
		</li>
	</ul>
	<button name="send" title="送信" class="btn_style01" type="submit" disabled="disabled" id="js-submit-button">送信</button>
	<?= $this->Form->end() ?>
	<p>ご入力いただいたメールアドレスなどの個人情報およびメッセージ内容に含まれる個人情報について、当社が定めるプライバシーポリシーに従い適切にお取り扱いいたします。お問い合わせの際は、必ず<a href="/company/privacypolicy">プライバシーポリシー</a>をご一読いただき、同意のうえご利用ください。</p>
</div>

<script>
function syncerRecaptchaCallback( code )
{
	if(code != "")
	{
		document.getElementById("js-submit-button").removeAttribute("disabled");
	}
}
</script>
