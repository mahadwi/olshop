<script setup>
  import { Link } from "@inertiajs/vue3";

  const activeMaster = route().current('users.*') || route().current('roles.*') || route().current('product-category.*') || route().current('vendor.*') || route().current('product.*')  || route().current('brand.*') ? '' : 'hidden'

</script>
<template>
  <aside
    id="sidebar"
    class="fixed top-0 left-0 z-20 md:flex flex-col flex-shrink-0 hidden w-64 h-full pt-16 font-normal duration-75 lg:flex transition-width"
    aria-label="Sidebar"
  >
    <div
      class="relative flex flex-col flex-1 min-h-0 pt-0 bg-white border-r border-gray-200 dark:bg-gray-800 dark:border-gray-700"
    >
      <div class="flex flex-col flex-1 pt-5 pb-4 overflow-y-auto">
        <div
          class="flex-1 px-3 space-y-1 bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700"
        >
          <ul class="pb-2 space-y-2">
            <li>
              <Link 
                class="flex items-center p-2 text-base text-gray-900 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700" 
                :href="route('dashboard')"
                
                :class="
                    route().current('dasbhoard.index')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                ">                
                <svg
                  class="w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                  <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                </svg>
                <span class="ml-3" sidebar-toggle-item>Dasbhoard</span>
              </Link>
            </li>
            <li>
              <button
                type="button"
                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                aria-controls="dropdown-crud"
                data-collapse-toggle="dropdown-crud"
              >
                <svg
                  class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                  aria-hidden="true"
                >
                  <path
                    clip-rule="evenodd"
                    fill-rule="evenodd"
                    d="M.99 5.24A2.25 2.25 0 013.25 3h13.5A2.25 2.25 0 0119 5.25l.01 9.5A2.25 2.25 0 0116.76 17H3.26A2.267 2.267 0 011 14.74l-.01-9.5zm8.26 9.52v-.625a.75.75 0 00-.75-.75H3.25a.75.75 0 00-.75.75v.615c0 .414.336.75.75.75h5.373a.75.75 0 00.627-.74zm1.5 0a.75.75 0 00.627.74h5.373a.75.75 0 00.75-.75v-.615a.75.75 0 00-.75-.75H11.5a.75.75 0 00-.75.75v.625zm6.75-3.63v-.625a.75.75 0 00-.75-.75H11.5a.75.75 0 00-.75.75v.625c0 .414.336.75.75.75h5.25a.75.75 0 00.75-.75zm-8.25 0v-.625a.75.75 0 00-.75-.75H3.25a.75.75 0 00-.75.75v.625c0 .414.336.75.75.75H8.5a.75.75 0 00.75-.75zM17.5 7.5v-.625a.75.75 0 00-.75-.75H11.5a.75.75 0 00-.75.75V7.5c0 .414.336.75.75.75h5.25a.75.75 0 00.75-.75zm-8.25 0v-.625a.75.75 0 00-.75-.75H3.25a.75.75 0 00-.75.75V7.5c0 .414.336.75.75.75H8.5a.75.75 0 00.75-.75z"
                  ></path>
                </svg>
                <span
                  class="flex-1 ml-3 text-left whitespace-nowrap"
                  sidebar-toggle-item
                  >Data Master</span
                >
                <svg
                  sidebar-toggle-item
                  class="w-6 h-6"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </button>
              <ul id="dropdown-crud" :class="activeMaster" class="space-y-2 py-2">
                <li>
                  <Link :href="route('users.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"
                  
                  :class="
                    route().current('users.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "
                  
                  >
                    {{ lang().label.user }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('roles.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"
                  
                  :class="
                    route().current('roles.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "
                  
                  >
                    {{ lang().label.role }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('brand.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"
                  
                  :class="
                    route().current('brand.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "
                  
                  >
                    {{ lang().label.brand }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('product-category.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"
                  
                  :class="
                    route().current('product-category.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "
                  
                  >
                    {{ lang().label.product_category }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('product.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"
                  
                  :class="
                    route().current('product.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "
                  
                  >
                    {{ lang().label.product }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('vendor.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"
                  
                  :class="
                    route().current('vendor.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "
                  
                  >
                    {{ lang().label.vendor }}
                  </Link>
                </li>
              </ul>
            </li>
          </ul>
          <div class="pt-2 space-y-2">
            <a
              href="https://flowbite.com/docs/components/alerts/"
              target="_blank"
              class="flex items-center p-2 text-base text-gray-900 transition duration-75 rounded-lg hover:bg-gray-100 group dark:text-gray-200 dark:hover:bg-gray-700"
            >
              <svg
                class="flex-shrink-0 w-6 h-6 text-gray-500 transition duration-75 group-hover:text-gray-900 dark:text-gray-400 dark:group-hover:text-white"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"
                ></path>
              </svg>
              <span class="ml-3" sidebar-toggle-item>Components</span>
            </a>           
          </div>
        </div>
      </div>
    </div>
  </aside>
</template>
