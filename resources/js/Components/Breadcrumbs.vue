<template>
    <h1 v-if="itemsPopped" class="mb-8 font-bold text-3xl">
        <template v-for="(item, index) in itemsPopped" :key="index">
            <inertia-link class="text-indigo-400 hover:text-indigo-600" :href="item.url">
                {{ item.title }}
            </inertia-link>
            <span class="text-indigo-400 font-medium"> /</span>
        </template>
        {{ lastItem.title }}
    </h1>
    <h1 v-else class="mb-8 font-bold text-3xl">
        {{ lastItem.title }}
    </h1>
</template>

<script>
export default {
    props: {
        items: Object,
    },
    data() {
        return {
            lastTitle: String,
            titlesPopped: Array,
        }
    },
    beforeCreate() {
        this.itemsPopped = this.items;
        this.lastItem = this.itemsPopped.pop();
    }
}
// items example: [{ title: 'test', url: route('...') }, { ... }]
</script>
