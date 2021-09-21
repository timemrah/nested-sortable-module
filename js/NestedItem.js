class NestedItem
{

    constructor(Data, Route){

        this.Data  = Data;
        this.Route = Route;

        this.Html = {};
        this.Html.cloneWrapper = document.querySelector('#clone-wrapper .list-group-item');
        this.Html.categories   = document.getElementById('categories');

    }


    createCategoryDOM(){
        const $category = this.Html.cloneWrapper.cloneNode(true);
        $category.dataset.id = this.Data.ID;
        $category.querySelector('.list-group').dataset.upperId = this.Data.ID;
        $category.querySelector('.content > span').innerHTML = this.Data.TITLE;
        this.Html.wrapper = $category;
        return $category;
    }


    startSortable(){
        const self = this;
        new Sortable(self.Html.wrapper.querySelector(':scope > .nested-sortable'), {
            group: 'nested',
            animation: 150,
            swapThreshold: 0.65,
            onEnd: function (evt){
                const category = evt.item;
                const sendData = {
                    categoryId : parseInt(category.dataset.id),
                    groupId    : parseInt(self.Data.GROUP_ID),
                    newUpperId : evt.to.dataset.upperId,
                    oldUpperId : evt.from.dataset.upperId,
                    oldIndex   : evt.oldIndex,
                    newIndex   : evt.newIndex
                };

                if(sendData.newUpperId === sendData.oldUpperId && sendData.newIndex === sendData.oldIndex){
                    console.log("Sıra Değişmedi");
                    return false;
                }

                ajaxto.put(self.Route.sortCategories, sendData).success(json => {
                    console.log(json.data);
                });
            }
        });
    }


}