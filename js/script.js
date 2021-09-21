(()=>{

    const Html = {};
    Html.items = document.getElementById('wrapper');


    const Route = {
        getCategories  : Html.items.dataset.getAction,
        sortCategories : Html.items.dataset.sortAction
    };


    const Data = {
        groupId: Html.items.dataset.groupId
    };




    // KATEGORİLERİ ALIP BASALIM :
    ajaxto.get(Route.getCategories, {group_id: Data.groupId}).success(json => {

        ForEachNested(json.data, 0, function(item){
            let category   = new NestedItem(item, Route);
            let $item      = category.createCategoryDOM();
            let $findUpper = Html.items.querySelector(`div.list-group[data-upper-id="${item.UPPER_ID}"]`);
            $findUpper.append($item);
            category.startSortable();
        });

    });
    // KATEGORİLERİ ALIP BASALIM //




    //EN ÜST WRAPPER SORTABLE BAŞLANGIÇ :
    new Sortable(Html.items.querySelector(':scope > .nested-sortable'), {
        group: 'nested',
        animation: 150,
        swapThreshold: 0.65,
        onEnd: function (evt){
            const category = evt.item;
            const sendData = {
                categoryId : parseInt(category.dataset.id),
                groupId    : parseInt(Data.groupId),
                newUpperId : evt.to.dataset.upperId,
                oldUpperId : evt.from.dataset.upperId,
                oldIndex   : evt.oldIndex,
                newIndex   : evt.newIndex
            };

            if(sendData.newUpperId === sendData.oldUpperId && sendData.newIndex === sendData.oldIndex){
                console.log("Sıra Değişmedi");
                return false;
            }

            ajaxto.put(Route.sortCategories, sendData).success(json => {
                console.log(json.data);
            });
        }
    });
    //WRAPPER SORTABLE BAŞLANGIÇ //

})();







/* İç içe geçen kategorileri dıştan içe doğru sıralayarak bize verir.
 * Böylece kategoriler ekrana dıştan içe sırayla basılır ve hata üretmez.
 * 'ForEachNested' fonksiyonunu kullanmazsanız olmayan bir kategori içine
 * kategori yerleştirmeye çalışmanız oldukça olasıdır ve bu durum js hatası
 * oluşmasına sebep olur. */
function ForEachNested(data, startUpperId, callback){

    //İç içe geçmiş kategorileri sırasıyla çağırmayı başardığımız işlem kodları.
    let process = function(upper, callback){
        let items = findItems(upper); //Bu dış kabuğa sahip tüm item'leri getir.
        for(let i in items){
            let item = items[i];
            callback(item); //Her bir item'de bu aracı kullanırken item kullanılarak kod yazılabilmesini sağlıyoruz.
            //Eğer işlem yapılacak item kaldıysa bu item'lerinde altında iç içe geçmiş item varmı bak.
            if(data.length){
                process(item.ID, callback);
            }
        }
    }

    //Kapsayıcı item'e ait item'leri ver. Bu yardımcı metoddur.
    let findItems = function(upper){

        /* "=" operatörü dizileri referans olarak atıyor. Döngünün sağlıklı çalışması için bu şekilde clone luyoruz.
        * Çünkü kullanılan item'i diziden silerek bir sonraki dizi içinde aramada performans artışı sağlıyoruz. */
        let newData = [...data];
        let items   = [];

        for(let i in data){
            let item = data[i];
            if(parseInt(item.UPPER_ID) === parseInt(upper)){
                items.push(item);
                newData.splice(newData.indexOf(item), 1); //Kullanılan item'i diziden siliyoruz.
            }
        }

        //Aramadan sonra bulunan item'lerden temizlenmiş diziyi bir sonraki arama işlemi için arama yapılacak diziye aktarıyoruz.
        data = newData;

        return items;
    }

    //Recursive işleminin ilk çalıştırmasını yap.
    process(startUpperId, callback);

}