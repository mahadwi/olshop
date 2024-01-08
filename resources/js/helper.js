export { priceFormat, round, truncate, dataSwitch, calculatePpn };

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

function truncate (value, length) {
    let isi = value ?? '';
    return isi.length > length ? value.slice(0, length) + "......" : isi;
}

function calculatePpn (price, ppn) {
    let tmpPrice = parseFloat(price);

    let calc = (ppn / 100) * price;

    tmpPrice += calc;
    
    return tmpPrice;
}

const dataSwitch = [
    {
        name: "Yes",
        value: true
    },
    {
        name: "No",
        value: false
    }
];
