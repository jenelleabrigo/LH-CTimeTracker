{{ assign $site.title = 'お問い合わせ [完了] | ' . $site.title }}
{{ assign $site.description = 'お問い合わせ [完了] | ' . $site.description }}
{{ assign $site.url = 'contact/result.html' }}

{{ transclude '../template/siteframe' }}

{{ include head './include/head/input' }}
{{ include foot './include/foot/input' }}

{{ section contents }}

<div class="c-headline">
    <div class="u-layout">
        <h1 class="c-headline__text">お問い合わせ</h1>
        <p class="c-headline__catch">Contact</p>
    </div>
</div>

<div class="c-topic-path">
    <div class="u-layout">
        <ul class="c-topic-path__row">
            <li class="c-topic-path__col"><a href="{{ base }}/">ホーム</a></li>
            <li class="c-topic-path__col"><span>お問い合わせ</span></li>
        </ul>
    </div>
</div>

    <div class="u-layout p-contact">

        <div class="p-contact__result">
            <h2 class="p-contact__result__head">お問い合わせいただきありがとうございます</h2>
            <p>後ほど担当者より、折り返しご連絡させていただきますのでしばらくお待ちください。</p>
        </div>

    </div>

{{ end section contents }}
