<template>
  <div id="main-nav" v-on-clickaway="closeSidebar" :class="$root.sidebarOpen ? '' : 'hidden'">
      <slot></slot>
  </div>
</template>

<script>
import { directive as onClickaway } from 'vue-clickaway';
export default {
    directives: {
        onClickaway: onClickaway,
    },
    name: 'Sidebar',
    methods: {
        closeSidebar(e){
            if(e.target.parentElement.id == 'closeSidebarBtn' || e.target.id == 'closeSidebarBtn') return;
            this.$root.sidebarOpen = false;
        }
    }
}
</script>

<style>
</style>
