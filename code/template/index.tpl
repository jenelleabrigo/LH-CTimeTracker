{{ assign $site.is_index = true }}

{{ transclude './template/siteframe' }}

{{ include head './include/head/index' }}
{{ include foot './include/foot/index' }}

{{ section contents }}

<section class="p-index">
    <div class="p-index__upper">
        <h1>Welcome Jenelle Loise!</h1>
    </div>

    <div class="p-index__timelog">
        <div class="p-index__timelog__in">
            <p class="p-index__timelog__text">Time In:</p>
            <div class="p-index__timelog__detail">
                <input class="p-index__timelog__date" id="datein" name="datein">
                <input class="p-index__timelog__time" id="timein" name="timein">
                <button class="p-index__timelog__now"></button>
            </div>
        </div>
        <div class="p-index__timelog__out">
            <p class="p-index__timelog__text">Time Out:</p>
            <div class="p-index__timelog__detail">
                <input class="p-index__timelog__date" id="dateout" name="dateout">
                <input class="p-index__timelog__time" id="timeout" name="timeout">
                <button class="p-index__timelog__now"></button>
            </div>
        </div>
    </div>
    
    <div class="p-index__task">
        <p class="p-index__task__text">Task Today:</p>
        <div class="p-index__task__content">
            <input class="p-index__task__content__item" type="text" name="" value="">
            <input class="p-index__task__content__deadline" type="date" name="" value="">
            <input class="p-index__task__content__remarks" type="text" name="" value="">
            <label class="c-customized-checkbox__container">
                <input class="c-customized-checkbox__input" name="checkBox" type="checkbox" required>
                <span class="c-customized-checkbox__input__checkmark"></span>
            </label>
        </div>
    </div>
</section>


{{ end section contents }}
