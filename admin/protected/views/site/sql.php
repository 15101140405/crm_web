<div class="form">

    <form id="data-center-form" action="/admin/site/sql" method="post">
        <p class="note">标记 <span class="required">*</span> 的字段是必填项.</p>

        <div class="row">
            <label for="sql" class="required">sql语句 <span class="required">*</span></label>
            <input size="60" maxlength="127" name="sql" id="sql" type="text" value="<?php echo $sql; ?>">
        </div>

        <div class="row buttons">
            <input type="submit" name="yt0" value="执行"></div>

    </form>
</div>


<hr>
执行结果：<br>

<?php if (is_array($message)) {
        print_r(CJSON::encode($message));
    } else {
        echo $message;
    }
?>