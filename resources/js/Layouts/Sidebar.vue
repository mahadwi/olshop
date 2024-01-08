<script setup>
  import { Link } from "@inertiajs/vue3";

  const activeMaster = route().current('users.*') || route().current('roles.*') || route().current('product-category.*') || route().current('vendor.*') || route().current('product.*')  || route().current('brand.*') || route().current('group-coa.*') || route().current('coa.*') || route().current('color.*') || route().current('banner.*') || route().current('gallery.*') || route().current('member.*') || route().current('group-asset.*') || route().current('asset.*') || route().current('event.*') || route().current('promo-subscribe.*') || route().current('review.*') || route().current('suggestion.*') || route().current('message.*') ? '' : 'hidden'

  const activeSetting = route().current('about.*') || route().current('contact.*') || route().current('return-police.*') || route().current('privacy-police.*') || route().current('term-condition.*')  || route().current('delivery-shipping.*') || route().current('customer-care.*') || route().current('faq.*') || route().current('voucher.*') || route().current('authentication.*') || route().current('work-with-us.*') || route().current('consignment.*') ? '' : 'hidden'

  const activeTransaction = route().current('pos.*') ? '' : 'hidden'

  const activeAsset = route().current('pembelian-asset.*') ? '' : 'hidden'


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
                <span class="ml-3" sidebar-toggle-item>Dashboard</span>
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
                  <Link :href="route('color.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('color.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "

                  >
                    {{ lang().label.color }}
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
                    {{ lang().label.category }}
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
                <li>
                  <Link :href="route('group-coa.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('group-coa.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "

                  >
                    {{ lang().label.group_coa }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('coa.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('coa.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "

                  >
                    {{ lang().label.coa }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('banner.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('banner.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "

                  >
                    {{ lang().label.banner }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('gallery.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('gallery.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "

                  >
                    {{ lang().label.gallery }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('member.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('member.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "

                  >
                    {{ lang().label.member }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('group-asset.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('group-asset.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "

                  >
                    {{ lang().label.group_asset }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('asset.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('asset.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "

                  >
                    {{ lang().label.asset }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('event.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('event.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "

                  >
                    {{ lang().label.event }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('subscribe.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('subscribe.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "
                  >
                    {{ lang().label.subscribe_splash }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('promo-subscribe.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('promo-subscribe.*')
                    ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                    "
                    >
                      {{ lang().label.promo_subscribe }}
                    </Link>
                </li>
                <li>
                  <Link :href="route('review.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('review.*')
                    ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                    "
                    >{{ lang().label.review }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('suggestion.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('suggestion.*')
                    ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                    "
                    >{{ lang().label.suggestion }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('message.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('message.*')
                    ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                    "
                    >{{ lang().label.message }}
                  </Link>
                </li>
              </ul>
            </li>           
            <li>
              <button
                type="button"
                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                aria-controls="dropdown-transaction"
                data-collapse-toggle="dropdown-transaction"
              >
                <svg class="w-6 h-6 text-gray-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                  <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 13 6-6m-5-.5h.01m2.98 7H11m1.007-11.313a2.75 2.75 0 0 0 2.1.87 2.745 2.745 0 0 1 2.837 2.837 2.749 2.749 0 0 0 .87 2.1 2.747 2.747 0 0 1 0 4.014 2.748 2.748 0 0 0-.87 2.1 2.746 2.746 0 0 1-2.837 2.837 2.75 2.75 0 0 0-2.1.87 2.748 2.748 0 0 1-4.014 0 2.75 2.75 0 0 0-2.1-.87 2.744 2.744 0 0 1-2.837-2.837 2.749 2.749 0 0 0-.87-2.1 2.747 2.747 0 0 1 0-4.014 2.75 2.75 0 0 0 .87-2.1 2.745 2.745 0 0 1 2.838-2.837 2.749 2.749 0 0 0 2.1-.87 2.748 2.748 0 0 1 4.013 0Z"/>
                </svg>
                <span
                  class="flex-1 ml-3 text-left whitespace-nowrap"
                  sidebar-toggle-item
                  >Transaction</span
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
              <ul id="dropdown-transaction" :class="activeTransaction" class="space-y-2 py-2">
                <li>
                  <Link :href="route('pos.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('pos.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "

                  >
                    {{ lang().label.point_of_sales }}
                  </Link>
                </li>
              </ul>
            </li>
            <li>
              <button
                type="button"
                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                aria-controls="dropdown-asset"
                data-collapse-toggle="dropdown-asset"
              >
                <svg class="w-6 h-6 text-gray-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                  <path stroke="currentColor" stroke-linejoin="round" stroke-width="2" d="M8 8v1h4V8m4 7H4a1 1 0 0 1-1-1V5h14v9a1 1 0 0 1-1 1ZM2 1h16a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1Z"/>
                </svg>
                <span
                  class="flex-1 ml-3 text-left whitespace-nowrap"
                  sidebar-toggle-item
                  >Asset</span
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
              <ul id="dropdown-asset" :class="activeAsset" class="space-y-2 py-2">
                <li>
                  <Link :href="route('pembelian-asset.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('pembelian-asset.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "

                  >
                    {{ lang().label.pembelian_asset }}
                  </Link>
                </li>
              </ul>
            </li>
            <li>
              <button
                type="button"
                class="flex items-center w-full p-2 text-base text-gray-900 transition duration-75 rounded-lg group hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                aria-controls="dropdown-setting"
                data-collapse-toggle="dropdown-setting"
              >
                <svg class="w-6 h-6 text-gray-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M18 7.5h-.423l-.452-1.09.3-.3a1.5 1.5 0 0 0 0-2.121L16.01 2.575a1.5 1.5 0 0 0-2.121 0l-.3.3-1.089-.452V2A1.5 1.5 0 0 0 11 .5H9A1.5 1.5 0 0 0 7.5 2v.423l-1.09.452-.3-.3a1.5 1.5 0 0 0-2.121 0L2.576 3.99a1.5 1.5 0 0 0 0 2.121l.3.3L2.423 7.5H2A1.5 1.5 0 0 0 .5 9v2A1.5 1.5 0 0 0 2 12.5h.423l.452 1.09-.3.3a1.5 1.5 0 0 0 0 2.121l1.415 1.413a1.5 1.5 0 0 0 2.121 0l.3-.3 1.09.452V18A1.5 1.5 0 0 0 9 19.5h2a1.5 1.5 0 0 0 1.5-1.5v-.423l1.09-.452.3.3a1.5 1.5 0 0 0 2.121 0l1.415-1.414a1.5 1.5 0 0 0 0-2.121l-.3-.3.452-1.09H18a1.5 1.5 0 0 0 1.5-1.5V9A1.5 1.5 0 0 0 18 7.5Zm-8 6a3.5 3.5 0 1 1 0-7 3.5 3.5 0 0 1 0 7Z"/>
                </svg>
                <span
                  class="flex-1 ml-3 text-left whitespace-nowrap"
                  sidebar-toggle-item
                  >Setting</span
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
              <ul id="dropdown-setting" :class="activeSetting" class="space-y-2 py-2">
                <li>
                  <Link :href="route('about.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('about.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "

                  >
                    {{ lang().label.about_us }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('contact.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('contact.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "

                  >
                    {{ lang().label.contact }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('return-police.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('return-police.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "

                  >
                    {{ lang().label.return_police }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('privacy-police.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('privacy-police.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "

                  >
                    {{ lang().label.privacy_police }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('term-condition.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('term-condition.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "
                  >
                    {{ lang().label.term_condition }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('delivery-shipping.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('delivery-shipping.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "
                  >
                    {{ lang().label.delivery_shipping }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('customer-care.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('customer-care.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "
                  >
                    {{ lang().label.customer_care }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('faq.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('faq.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "
                  >
                    {{ lang().label.faq }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('voucher.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('voucher.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "
                  >
                    {{ lang().label.voucher }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('authentication.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('authentication.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "
                  >
                    {{ lang().label.authentication }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('work-with-us.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('work-with-us.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "
                  >
                    {{ lang().label.work_with_us }}
                  </Link>
                </li>
                <li>
                  <Link :href="route('consignment.index')" class="text-base text-gray-900 rounded-lg flex items-center p-2 group hover:bg-gray-100 transition duration-75 pl-11 dark:text-gray-200 dark:hover:bg-gray-700"

                  :class="
                    route().current('consignment.*')
                        ? 'bg-gray-100 dark:bg-gray-700'
                        : ''
                  "
                  >
                    {{ lang().label.consignment }}
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
