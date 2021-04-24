<?php $this->assign('keywords', $keywords); ?>
<?php $this->assign('description', $description); ?>
<?php $this->assign('title', $title); ?>
<?php $this->assign('thumnail', $thumnail); ?>
<h2 class="btmBorder"><?= $h2 ?></h2>
<p class="form-instructions">お問い合わせは以下のメールフォームからお気軽にお送りください。<br>スタッフよりご連絡させていただきます。</p>
<p class="form-instructions"><span class="required">* 必須項目</span></p>
<p>　<?= $this->Flash->render() ?></p>
<?= $this->Form->create($contact, ['id' => 'contactForm']) ?>
<ul class="form-listWide clearfix">
	<li>
		<label for="name" class="required"><em>*</em>お名前</label>
		<div class="input-box">
			<?php echo $this->Form->input('name', ['class'=> 'input-text required-entry',
			'title' => 'お名前', 'label' => false]); ?>
		</div>
	</li>
	<li>
		<label for="furigana" class="required"><em>*</em>フリガナ</label>
		<div class="input-box">
			<?php echo $this->Form->input('furigana', ['class'=> 'input-text required-entry',
			'title' => 'フリガナ', 'label' => false]); ?>
		</div>
	</li>
	<li>
		<label for="zip" class="required"><em>*</em>郵便番号</label>
		<div class="input-box">
			<?php echo $this->Form->text('zip1', ['class'=> 'input-text required-entry w100',
			'title' => '郵便番号', 'label' => false]); ?>
			- <?php echo $this->Form->text('zip2', ['class'=> 'input-text required-entry w100',
			'title' => '郵便番号', 'label' => false, 'id' => 'divzip']); ?>
		</div>
	</li>
	<li>
		<label for="jyuusho" class="required"><em>*</em>住所</label>
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
	<li>
		<label for="phone_number" class="required"><em>*</em>お電話番号（ハイフン無し　例：0333330000</label>
		<div class="input-box">
			<?php echo $this->Form->text('phone_number', ['class'=> 'input-text required-entry',
			'title' => 'お電話番号', 'type' => 'number']); ?>
		</div>
	</li>
	<li>
		<label for="fax_number">FAX番号（ハイフン無し　例：0333330000</label>
		<div class="input-box">
			<?php echo $this->Form->text('fax_number', ['class'=> 'input-text required-entry',
			'title' => 'FAX番号', 'type' => 'number']); ?>
		</div>
	</li>
	<li>
		<label for="email" class="required"><em>*</em>メールアドレス</label>
		<div class="input-box">
			<?php echo $this->Form->input('email', ['class'=> 'input-text required-entry',
			'title' => 'メールアドレス', 'label' => false, 'type' => "email"]); ?>
		</div>
	</li>
	<li>
		<label for="subject" class="required"><em>*</em>お問い合わせ件名</label>
		<div class="input-box">
			<?php echo $this->Form->input('subject', ['class'=> 'input-text required-entry',
			'title' => 'お問い合わせ件名', 'label' => false, 'type' => "text"]); ?>
		</div>
	</li>
	<li>
		<label for="email" class="required"><em>*</em>お問い合わせ内容 </label>
		<div class="input-box">
			<?php echo $this->Form->textarea('naiyou', ['class'=> 'required-entry naiyo',
			'title' => 'お問い合わせ内容', 'label' => false, 'id' => 'comment',
			'rows' => '6', 'cols' => '5']); ?>
		</div>
	</li>
	<li>
		<label class="required"><em>*</em>プライバシーポリシー</label>
		<div class="magazine" id="label-agree">
			<input type="checkbox" value="1" name="agree" id="agree">同意する
		</div>
	</li>
	<li>
		<div class="g-recaptcha" data-callback="syncerRecaptchaCallback" data-sitekey=<?=GOOGLE_RECAPTHA_SITE_KEY?>></div>
	</li>
</ul>
<button type="submit" class="orangeBtn" title="送信" name="send" disabled="disabled" id="js-submit-button">送信</button>
<?= $this->Form->end() ?>
<p>ご入力いただいたメールアドレスなどの個人情報およびメッセージ内容に含まれる個人情報について、当社が定めるプライバシーポリシーに従い適切にお取り扱いいたします。お問い合わせの際は、必ず<a href="/company/privacypolicy">プライバシーポリシー</a>をご一読いただき、同意のうえご利用ください。</p>

<script>
function syncerRecaptchaCallback( code )
{
	if(code != "")
	{
		document.getElementById("js-submit-button").removeAttribute("disabled");
	}
}
</script>
