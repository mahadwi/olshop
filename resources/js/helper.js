export default priceFormat;

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
