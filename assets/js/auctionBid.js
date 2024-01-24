function auctionBid() {
    const auctionId = document.querySelector('#auction_id').value;
    const offerPrice = document.querySelector('#offer_price').value;

    addOffer(auctionId, offerPrice, updatePriceOnPage, showErrorMessage);
}

function updatePriceOnPage(newPrice) {
    document.querySelector('#current_auction_price').innerHTML = newPrice;
    showMessage('Nova ponuda je upsesno dodata');
}

function showErrorMessage(errorCode) {
    switch (errorCode) {
        case -10001 : showMessage('Niste prijavljeni!'); break;
        case -10002 : showMessage('Ne mozete da licitirate na svojoj aukciji!'); break;
        case -20001 : showMessage('Aukcija ne postoji!'); break;
        case -20002 : showMessage('Aukcija nije aktivna!'); break;
        case -20003 : showMessage('Aukcija se zavrsila!'); break;
        case -20004 : showMessage('Aukcija nije pocela!'); break;
        case -20005 : showMessage('Cena nije ispravna!'); break;
        case -20006 : showMessage('Greska prilikom dodavanja ponude!'); break;
        default: showMessage('Nepoznata greska!'); break;
    }
}

function showMessage(message) {
    document.querySelector('#message').innerHTML = message;

    setTimeout(function () {
        document.querySelector('#message').innerHTML = '';
    }, 5000);
}