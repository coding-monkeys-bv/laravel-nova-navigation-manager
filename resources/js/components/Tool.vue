<template>
    <div id="voicecode-navigation">
        <heading class="mb-6">{{ __('Navigatie') }}</heading>

        <div class="flex">
            <div class="w-full">

                <!-- When no navigation is chosen. -->
                <card class="relative">                    
                    <div class="flex flex-col items-center justify-center" style="min-height: 550px" v-if="navigation === null">                        
                        <h1 class="text-back text-2xl text-90 font-light my-6">
                            {{ __('Selecteer een navigatie') }}
                        </h1>                        
                    </div>
                </card>
                <!-- End when no navigation is chosen -->

                <!-- Start navigation editor -->
                <card v-if="navigation !== null" class="p-6">

                    <div class="mb-6  float-right">
                        <button class="btn btn-default btn-primary" @click="addItem">{{ __('Item toevoegen') }}</button>
                        <button class="btn btn-default btn-secondary" @click="getNavigations">{{ __('Vernieuwen') }}</button>
                    </div>

                    <p class="text-xl mb-8">{{ navigation.name }}</p>
                
                    <draggable-tree :data="data" draggable @drag="onDrag" @drop="onDrop" @change="onChange">                        
                        <div slot-scope="{data, store}">

                            <template v-if="!data.isDragPlaceHolder">

                                <div :class="['relative', data.children && data.children.length ? 'parent-item' : '']"> 
                                    
                                    <span :class="['', data.editable ? 'block bg-30 p-3' : '']">
                                        <span class="ml-3 cursor-pointer">
                                            <strong v-if="data.children && data.children.length && !data.editable" @click="store.toggleOpen(data)">
                                                {{ data.open ? '-' : '+' }} &nbsp;
                                            </strong>
                                        </span>
                                    
                                        <!-- When item is not editable -->
                                        <span class="item-name" v-if="!data.editable">
                                            {{ data.name }}
                                        </span>

                                        <!-- When item is editable -->
                                        <div v-if="data.editable" class="p-0">

                                            <div class="w-full flex flex-col">
                                                <div class="mb-6">
                                                    <label class="block font-bold mb-1">{{ __('Naam') }}</label>
                                                    <input class="form-control w-full form-input" v-model="data.name" placeholder="Naam" @change="updateItem(data)">
                                                </div>
                                                <div class="mb-6">
                                                    <label class="block font-bold mb-1">{{ __('URL') }}</label>
                                                    <input class="form-control w-full form-input" v-model="data.url" placeholder="https://" @change="updateItem(data)">
                                                </div>
                                                <div class="mb-6">
                                                    <label class="block font-bold mb-1">{{ __('Openen in') }}</label>
                                                    <select class="form-control w-full form-input" v-model="data.target" @change="updateItem(data)">
                                                        <option value="_blank">{{ __('Nieuw venster') }}</option>
                                                        <option value="_parent">{{ __('Huidig venster') }}</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>

                                    </span>

                                    <div class="absolute pin-r pin-t mt-3 mr-3">
                                        <span @click="editItem(data)">
                                            <icon class="text-70" type="view" view-box="0 0 22 16" width="22" height="16" v-if="data.editable" />
                                            <icon class="text-70" type="edit" view-box="0 0 20 20" width="16" height="16" v-if="!data.editable" />
                                        </span>
                                        <span @click="deleteItem(data.id)">
                                            <icon class="text-70" type="delete" view-box="0 0 20 20" width="16" height="16" />
                                        </span>
                                    </div>

                                </div>

                            </template>
                        </div>
                    </draggable-tree>
                    
                </card>
                <!-- End navigation editor -->

            </div>
        </div>

    </div>
</template>

<script>
import swal from 'sweetalert';
import {DraggableTree} from 'vue-draggable-nested-tree'
import * as th from 'tree-helper'

export default {

    components: {
        DraggableTree
    },
    
    data() {
        return {
            data: [],
            navigation: null,
            navigations: [],
            maxLevel: null,
            apiUrl: '/nova-vendor/voicecode/navigation-manager/',
        }
    },

    mounted() {
        this.getNavigations()
    },

    methods: {

        getNavigations() {
            var self = this;

            // Fetch navigations.
            axios.get(this.apiUrl).then(response => {
                this.navigations = response.data
                this.selectNavigation(self.navigations[0]);
            });
        },
            
        selectNavigation(navigation) {
            this.navigation = navigation
            this.maxLevel = navigation.depth

            // Get navigation items.
            axios.get(this.apiUrl + navigation.id).then(response => {
                this.data = response.data
            });
        },

        addItem() {

            var data = {}
            data.navigation_id = this.navigation.id
            data.name = 'Item'
            data.url = '#'
            data.target = '_parent'

            // Add item via API.
            axios.post(this.apiUrl + this.navigation.id + '/items/add', data).then(response => { 
                // Reload data.
                this.getNavigations();
            }).catch(error => {
                console.log(error)
            });
        },

        deleteItem(id) {

            swal({
                title: "Weet je het zeker?",
                text: "Ook onderliggende items zullen worden verwijderd.",
                icon: "warning",
                buttons: ['Annuleren', 'OK'],
                dangerMode: true,
            })
            .then((willDelete) => {
                if (willDelete) {
                    
                    // Delete item.
                    axios.delete(this.apiUrl + this.navigation.id + '/items/' + id).then(response => {
                        // Reload tree
                        this.selectNavigation(this.navigation);
                    }).catch(error => {
                        console.log(error.response.data.errors)
                    });

                }
            });
        },

        onDrag(node) {
            const {maxLevel} = this

            let nodeLevels = 1

            th.depthFirstSearch(node, (childNode) => {
                if (childNode._vm.level > nodeLevels) {
                    nodeLevels = childNode._vm.level
                }
            })
            
            nodeLevels = nodeLevels - node._vm.level + 1
            const childNodeMaxLevel = maxLevel - nodeLevels
            
            th.depthFirstSearch(this.data, (childNode) => {
                if (childNode === node) {
                    return 'skip children'
                }
                
                if (!childNode._vm) {
                    console.log(childNode);
                }
                this.$set(childNode, 'droppable', childNode._vm.level <= childNodeMaxLevel)
            })
        },

        onDrop(node) {

            var self = this;
            var postData = {}
            postData.items = []
            
            this.data.forEach(function(item) {

                // Get index of parent items.
                postData.items.push({ id: item.id, order: self.data.indexOf(item)})       
                
                item.children.forEach(function(child) {
                    postData.items.push({ id: child.id, order: item.children.indexOf(child), parent_id: child.parent.id})  
                })
            })
           
            axios.put(this.apiUrl + this.navigation.id + '/items/positions', postData).catch(error => {
                console.log(error.reponse.data.errors)
            });
        },

        editItem(data) {
            data.editable = !data.editable
            data.draggable = !data.draggable
        }, 
        
        updateItem(data) {
            
            // Setup data.
            var item = {}
            item.id = data.id;
            item.name = data.name;
            item.url = data.url;
            item.target = data.target;
            
            // Update data.
            axios.put(this.apiUrl + this.navigation.id + '/items/' + data.id, item).then(response => {
                // this.getData(); // Refresh data.
            }).catch(error => {
                console.log(error)
            })
        }
    },
}
</script>