
<script>
$(document).ready(function(){
    $('.calendarWidget').each(function(){
        var tableOffset = 15;
        var prevMonthLink = $(this).find('.monthNav .prevMonth');
        var nextMonthLink = $(this).find('.monthNav .nextMonth');
        var currMonth = $(this).find('.monthNav .currMonth');
        
        var calWrapper = $(this).find('.calendarWrapper');
        var calTables = $(this).find('.calendarWrapper table');
        var calTableWidth = calTables.outerWidth();
        var calTableCount = calTables.length;
        var currTable = calTables.filter('.curr');
        
        calWrapper.css('width', calTableCount * ( calTableWidth + tableOffset )+'px');
        calWrapper.css('left', -(currTable.position().left)+'px');
        currMonth.text(currTable.attr('title'));
        
        prevMonthLink.click(function(){
            var prevTable = currTable.prev('table');
            
            if (prevTable.length == 0) return false;
            
            calTables.removeClass('curr');
            prevTable.addClass('curr');
            currTable = prevTable;
            
            calWrapper.animate({
                left: -(prevTable.position().left)+'px'
            }, 500, 'swing');
            
            currMonth.fadeOut('fast', function(){
                $(this).text(currTable.attr('title')).fadeIn('fast');
            });
            
            return false;
        });
        
        nextMonthLink.click(function(){
            var nextTable = currTable.next('table');
            
            if (nextTable.length == 0) return false;
            
            calTables.removeClass('curr');
            nextTable.addClass('curr');
            currTable = nextTable;
            
            calWrapper.animate({
                left: -(nextTable.position().left)+'px'
            }, 500, 'swing');
            
            currMonth.fadeOut('fast', function(){
                $(this).text(currTable.attr('title')).fadeIn('fast');
            });
            
            return false;
        });
    });
});
</script>

<div class="calendarWidget">
    <div class="monthNav">
        <a href="#" class="prevMonth"><span></span></a><span class="currMonth">&nbsp;</span><a href="#" class="nextMonth"><span></span></a>
    </div>
    <div class="calendarBody">
        <div class="calendarWrapper">
            <?php end($calendar); $lastMonth = key($calendar); 
            $currDate = date('F Y', strtotime(Yii::app()->request->getQuery('date')));
                        
            foreach ($calendar as $month => $dates) : 
                $tblClass = ($lastMonth == $month || $month == $currDate) ? 'curr' : ''; 
            ?>
            
            <?php echo $this->generate_calendar(date('Y', strtotime($month)), date('m', strtotime($month)), $dates, 4, NULL, 1, array(), array('class'=>$tblClass)); ?>
                        
            <?php endforeach; ?>
        </div>
    </div>
</div>