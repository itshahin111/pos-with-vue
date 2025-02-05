
<template>

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <input placeholder="Search..." class="form-control mb-2 w-auto form-control-sm" type="text" v-model="searchValue">
                            <EasyDataTable buttons-pagination alternating :headers="Header" :items="Item" :rows-per-page="10" :search-field="searchField"  :search-value="searchValue">
                                <template #item-number="{ number,player }">
                                    <button class="btn btn-success mx-3 btn-sm" @click="itemClick(number,player)">Edit</button>
                                    <button class="btn btn-danger btn-sm" @click="itemClick(number,player)">Delete</button>
                                </template>
                            </EasyDataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</template>

<script setup>
import {ref} from "vue";


const Header = [
    { text: "No", value: "no" },
    { text: "Name", value: "name"},
    { text: "Action", value: "number"},
];


const Item = ref([
        { "no": "1", "name": "Fruits" },
        { "no": "2", "name": "Vegetables" },
        { "no": "3", "name": "Dairy Products" },
        { "no": "4", "name": "Meat and Poultry" },
        { "no": "5", "name": "Seafood" },
        { "no": "6", "name": "Grains and Cereals" },
        { "no": "7", "name": "Bakery and Pastry" },
        { "no": "8", "name": "Beverages" },
        { "no": "9", "name": "Snacks and Appetizers" },
        { "no": "10", "name": "Spices and Herbs" },
        { "no": "11", "name": "Sauces and Condiments" },
        { "no": "12", "name": "Frozen Foods" },
        { "no": "13", "name": "Canned and Preserved Foods" },
        { "no": "14", "name": "Organic Foods" },
        { "no": "15", "name": "Gluten-Free Products" },
        { "no": "16", "name": "Vegan and Plant-Based Foods" },
        { "no": "17", "name": "Sweets and Desserts" },
        { "no": "18", "name": "Nuts and Seeds" },
        { "no": "19", "name": "Oils and Fats" },
        { "no": "20", "name": "Ready-to-Eat Meals" }
    ]
)


const itemClick = (number,player) => {
    alert(`Number is=${number} & Player Name is=${player}`)
}


</script>
