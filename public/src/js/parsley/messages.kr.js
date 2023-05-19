window.ParsleyConfig = window.ParsleyConfig || {};

(function ($) {
	window.ParsleyConfig = $.extend( true, {}, window.ParsleyConfig, {
		messages: {
			// parsley //////////////////////////////////////
			defaultMessage: "This value seems to be invalid."
			, type: {
				email:        "유효한 이메일 형식이 아닙니다."
				, url:        "유효한 URL 형식이 아닙니다."
				, urlstrict:  "유효한 URL 형식이 아닙니다."
				, number:     "실수만 입력이 가능합니다."
				, digits:     "정수만 입력이 가능합니다."
				, dateIso:    "유효한 날짜 형식이 아닙니다(YYYY-MM-DD)."
				, alphanum:   "알파벳과 숫자만 입력이 가능합니다."
				, phone:      "유효한 전화번호 형식이 아닙니다."
			}
			, notnull:        "This value should not be null."
			, notblank:       "This value should not be blank."
			, required:       "필수 입력 항목입니다."
			, regexp:         "잘못된 형식입니다."
			, min:            "%s이상의 숫자를 입력하셔야 합니다."
			, max:            "%s이하의 숫자를 입력하셔야 합니다."
			, range:          "%s와 %s사이의 숫자를 입력하셔야 합니다."
			, minlength:      "%s글자 이상을 입력하셔야 합니다."
			, maxlength:      "%s글자 이하를 입력하셔야 합니다."
			, rangelength:    "%s와 %s사이의 글자수만 입력 가능합니다."
			, mincheck:       "적어도 %s개의 항목을 선택하셔야 합니다."
			, maxcheck:       "최대 %s개의 항목만을 선택하실 수 있습니다."
			, rangecheck:     "%s와 %s개 사이의 항목만을 선택하실 수 있습니다."
			, equalto:        "입력값이 같지 않습니다."

			// parsley.extend ///////////////////////////////
			, minwords:       "%s개 이상의 단어를 입력하셔야 합니다."
			, maxwords:       "%s개 이하의 단어만을 입력하실 수 있습니다."
			, rangewords:     "%s와 %s개 사이의 단어만을 입력하실 수 있습니다."
			, greaterthan:    "%s보다 큰 숫자를 입력하셔야 합니다."
			, lessthan:       "%s보다 작은 숫자를 입력하셔야 합니다."
			, beforedate:     "%s 이전의 날짜를 입력하셔야 합니다."
			, afterdate:      "%s 이후의 날짜를 입력하셔야 합니다."
			, americandate:	  "유효한 날짜 형식이 아닙니다(MM/DD/YYYY)."
		}
	});
}(window.jQuery || window.Zepto));