export { priceFormat, round };

function priceFormat(amount = 0) {
    var rupiah = "";
    var amountRev = amount.toString().split("").reverse().join("");
    for (var i = 0; i < amountRev.length; i++) {
        if (i % 3 == 0) rupiah += amountRev.substr(i, 3) + ".";
    }
    return (        
        rupiah
            .split("", rupiah.length - 1)
            .reverse()
            .join("")
    );
}

function round(num, decimalPlaces = 0) {
    var p = Math.pow(10, decimalPlaces);
    var n = (num * p) * (1 + Number.EPSILON);
    return Math.round(n) / p;
}