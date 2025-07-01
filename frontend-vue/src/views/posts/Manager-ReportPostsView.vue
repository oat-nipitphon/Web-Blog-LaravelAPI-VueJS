<template>
  <div class="p-6 bg-gray-50 min-h-screen">
    <div
      class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6"
    >
      <h1 class="text-2xl font-bold text-gray-800">Dashboard Manage Posts</h1>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
      <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">โพสต์ทั้งหมด</p>
        <p class="text-2xl font-bold">540</p>
      </div>
      <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">ออนไลน์</p>
        <p class="text-2xl font-bold text-green-600">400</p>
      </div>
      <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">รอตรวจสอบ</p>
        <p class="text-2xl font-bold text-yellow-500">120</p>
      </div>
      <div class="bg-white p-4 rounded-xl shadow text-center">
        <p class="text-sm text-gray-500">ถูกบล็อก</p>
        <p class="text-2xl font-bold text-red-600">20</p>
      </div>
    </div>

    <!-- Posts Table -->
    <div class="overflow-x-auto bg-white rounded-xl shadow">
      <table class="w-full text-sm text-gray-700">
        <thead class="bg-gray-100 text-xs uppercase">
          <tr>
            <th class="text-center px-4 py-3 font-semibold">#</th>
            <th class="text-left px-4 py-3 font-semibold">Title</th>
            <th class="text-left px-4 py-3 font-semibold">Owner</th>
            <th class="text-center px-4 py-3 font-semibold">Type</th>
            <th class="text-center px-4 py-3 font-semibold">Status</th>
            <th class="text-center px-4 py-3 font-semibold">Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr
            class="border-b hover:bg-gray-50 transition"
            v-for="n in 5"
            :key="n"
          >
            <td class="text-center px-4 py-3">{{ n }}</td>
            <td class="px-4 py-3 font-medium text-gray-800">
              Post Title {{ n }}
            </td>
            <td class="px-4 py-3">User {{ n }}</td>
            <td class="text-center px-4 py-3">Blog</td>
            <td class="text-center px-4 py-3">
              <span
                class="px-3 py-1 rounded-full text-xs font-semibold"
                :class="
                  n % 3 === 0
                    ? 'bg-green-100 text-green-700'
                    : n % 2 === 0
                    ? 'bg-yellow-100 text-yellow-700'
                    : 'bg-red-100 text-red-700'
                "
              >
                {{
                  n % 3 === 0 ? "active" : n % 2 === 0 ? "pending" : "blocked"
                }}
              </span>
            </td>
            <td class="text-center px-4 py-3">
              <Menu as="div" class="relative inline-block text-left">
                <div>
                  <MenuButton
                    class="inline-flex justify-center items-center px-4 py-2 bg-white border rounded-md shadow text-sm font-medium hover:bg-gray-50"
                  >
                    Options
                    <ChevronDownIcon class="ml-2 h-5 w-5 text-gray-500" />
                  </MenuButton>
                </div>
                <transition
                  enter-active-class="transition ease-out duration-100"
                  enter-from-class="transform opacity-0 scale-95"
                  enter-to-class="transform opacity-100 scale-100"
                  leave-active-class="transition ease-in duration-75"
                  leave-from-class="transform opacity-100 scale-100"
                  leave-to-class="transform opacity-0 scale-95"
                >
                  <MenuItems
                    class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                  >
                    <div class="py-1">
                      <MenuItem v-slot="{ active }">
                        <button
                          type="button"
                          :class="[
                            active
                              ? 'bg-gray-100 text-gray-900'
                              : 'text-gray-700',
                            'block w-full px-4 py-2 text-left text-sm',
                          ]"
                        >
                          Show
                        </button>
                      </MenuItem>
                      <MenuItem v-slot="{ active }">
                        <button
                          type="button"
                          :class="[
                            active
                              ? 'bg-gray-100 text-gray-900'
                              : 'text-gray-700',
                            'block w-full px-4 py-2 text-left text-sm',
                          ]"
                        >
                          Edit
                        </button>
                      </MenuItem>
                      <MenuItem v-slot="{ active }">
                        <button
                          type="button"
                          :class="[
                            active
                              ? 'bg-gray-100 text-gray-900'
                              : 'text-red-600',
                            'block w-full px-4 py-2 text-left text-sm',
                          ]"
                        >
                          Delete
                        </button>
                      </MenuItem>
                    </div>
                  </MenuItems>
                </transition>
              </Menu>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { Menu, MenuButton, MenuItem, MenuItems } from "@headlessui/vue";
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
</script>
