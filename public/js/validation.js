$.validator.addMethod("checkPassword", function (value, elem) {
    hasFocus = document.activeElement === elem;
    if (hasFocus === false) {
        var re = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,20}$/;
        return re.test(value);
    }
    return true;
},
"Password should be 8-20 Characters, atleast one Capital and one Small Letter, one numberic and special characters"
);

$.validator.addMethod("alpha", function (value, elem) {
        var re = /^[a-zA-Z .]+$/;
        return re.test(value);
    },
    "Only Capital, Small Letters, Spaces and Dot Allowed"
);

$.validator.addMethod("checkMobile", function (value, elem) {
        var re = /[6-9]{1}[0-9]{9}/;
        return re.test(value);
    },
    "Please enter a valid mobile number"
);

$.validator.addMethod("checkPan", function (value, elem) {
        var re = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/;
        return re.test(value);
    },
    "Please enter a valid PAN"
);

$.validator.addMethod("checkPassword", function (value, elem) {
        var re = /^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,20}$/;
        return re.test(value);
    },
    "Password should be 8-20 Characters, atleast one Capital and one Small Letter, one numberic and special characters"
);


$.validator.addMethod("equalPassword", function (value, elem, param) {
        if(value==param){
            return true;
        }
        else{
            return false;
        }
    },
    "Both the password should match"
);

$.validator.addMethod("alphaNum", function (value, elem) {
        var re = /^([a-zA-Z0-9]+)$/;
        return re.test(value);
    },
    "Only Capital, Small Letters and Numbers Allowed"
);

$.validator.addMethod("numeric", function (value, elem) {
        var re = /^[0-9]*$/;
        return re.test(value);
    },
    "Only Numbers Allowed"
);