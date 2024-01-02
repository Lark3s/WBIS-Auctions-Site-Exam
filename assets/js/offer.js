function addOffer(auctionId, price, callbackUpdatePrice, callbackOnError) {
    let formData = new FormData();
    formData.append('auction_id', auctionId);
    formData.append('price', price);

    fetch(BASE + 'api/offer/make', {
        credentials: 'include',
        method: 'POST',
        body: formData
    }).then(result =>result.json()).then(data => {
        if (data.error === 0) {
            refreshAuctionPrice(auctionId, callbackUpdatePrice);
        } else {
            callbackOnError(data.error);
        }
    });
}

function refreshAuctionPrice(auctionId, callback) {
    fetch(BASE + 'api/auction/' + auctionId).then(res => res.json()).then(data => {
        let price = data.auction.last_offer_price;
        callback(price);
    });
}