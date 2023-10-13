<script setup>
import { ref } from "vue";
import Navbar from "@/Layouts/Navbar.vue";
import Sidebar from "@/Layouts/Sidebar.vue";

// import SideBarVue from "@/Components/SideBar.vue";

</script>

<template>
    <Navbar />
    <div class="flex pt-16 overflow-hidden bg-gray-50 dark:bg-gray-900">
        <div id="main-content" class="relative w-full h-full overflow-y-auto bg-gray-50 lg:ml-64 dark:bg-gray-900">

            <Sidebar />

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
