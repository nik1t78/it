<h2>Часто задаваемые вопросы</h2>
<?php foreach($questions as $question): ?>
<div class="faq-item">
    <div class="faq-q-author"><?php echo $question->q_author; ?><span class="faq-q-date"><?php echo DateFormat::convertDbToStr($question->q_date); ?></span></div>
    <a href="#" class="faq-question"><?php echo $question->question; ?></a>
    <div class="faq-answer"><?php echo $question->answer; ?><div class="faq-a-author"><?php echo $question->a_author;?><span class="faq-a-date"><?php echo DateFormat::convertDbToStr($question->a_date); ?></span></div></div>
</div>
<?php endforeach; ?>

<script>
$(document).ready(function(){
    $('.faq-question').click(function(){
        $(this).next('.faq-answer').slideToggle();
        return false;
    });
});  
</script>