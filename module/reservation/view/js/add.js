var reservation_map = {};
for(var i = 0; i < reservations.length; i++){
    var r = reservations[i];
    if(reservation_map[r.date] == undefined){
        reservation_map[r.date] = [];
    }
    reservation_map[r.date].push(r);
}

$(function(){
    $('#month-date').datetimepicker({
		lang : 'cn',
		i18n : {
			cn : {
				months : [
					'1月','2月','3月','4月',
					'5月','6月','7月','8月',
					'9月','10月','11月','12月',
				],
				dayOfWeek : [
					"日", "一", "二", "三",
					"四", "五", "六",
				]
			}
		},
        inline : true,
		format : 'Y-m-d',
        timepicker : false,
        minDate : today,
        onSelectDate : function(ct, i){
            var month = ct.getMonth() + 1;
            var date = ct.getDate();
            $('#month').val(month);
            $('#date').val(date);

            var hours = $('.hourspan').children();
            hours.removeClass('disabled');
            hours.removeClass('selected');
            $('#hour_start, #hour_end').val('');

            var reservations = reservation_map[ct.dateFormat('Y-m-d')];
            if(reservations){
                for(var i = 0; i < reservations.length; i++){
                    var r = reservations[i];
                    var hour_start = parseInt(r.hour_start, 10);
                    var hour_end = parseInt(r.hour_end, 10);
                    for(var h = hour_start; h < hour_end; h++){
                        hours.eq(h).addClass('disabled');
                    }
                }
            }
        }
	});

    var hourspan = $('<div class="hourspan"></div>');
    for(var i = 0; i < 24; i++){
        var hour = $('<div></div>');
        hour.text(i + ':00');
        hourspan.append(hour);
    }
    $('#hourspan').after(hourspan);

    hourspan.on('click', 'div:not(.disabled)', function(e){
        var current_div = $(e.target);
        var hours = current_div.parent();
        var selected_div = hours.children('div.selected');

        if(selected_div.length > 1){
            selected_div.removeClass('selected');
            current_div.addClass('selected');
        }else if(selected_div.length == 1){
            var start = selected_div.index();
            var end = current_div.index();
            if(end > start){
                var divs = hours.children();
                for(var i = start + 1; i <= end; i++){
                    divs.eq(i).addClass('selected');
                }
            }else{
                selected_div.removeClass('selected');
                current_div.addClass('selected');
            }
        }else{
            current_div.addClass('selected');
        }

        selected_div = hours.children('div.selected');
        var hour_start = parseInt(selected_div.first().text(), 10);
        $('#hour_start').val(hour_start);
        var hour_end = parseInt(selected_div.last().text(), 10) + 1;
        $('#hour_end').val(hour_end);
    });

    $('#confirm-button').click(function(e){
        if($('#month').val() == '' || $('#date').val() == ''){
            makeToast('请选择日期。');
            e.preventDefault();
        }else if($('#hour_start').val() == '' || $('#hour_end').val() == ''){
            makeToast('请选择时间段。');
            e.preventDefault();
        }
    });
});
