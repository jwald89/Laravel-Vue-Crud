<script setup>
import { ref, reactive, onMounted } from "vue";
import axios from "axios";
import { useRoute, useRouter } from "vue-router";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import Dashboard from "../../Dashboard.vue";

const router = useRouter();
const route = useRoute();

let personalDetails = ref([]);
let meta = ref({ current_page: 1, links: [] });

const fetchDetails = async (url) => {
    if (url) {
        try {
            const response = await axios.get(url);
            personalDetails.value = response.data.data;
            meta.value.current_page = response.data.current_page;
            meta.value.links = response.data.links;
            meta.value.per_page = response.data.per_page;
            console.log(response.data);
            if (!response.data.from && response.data.total !== 0) {
                fetchDetails(
                    `/api/personal-details?page=${meta.value.current_page - 1}`
                );
            }
        } catch (error) {
            console.error("Error fetching personal details:", error);
        }
    }
};

onMounted(() => {
    fetchDetails(`/api/personal-details?page=${route.query.page}` || "");
});

const deleteDetail = async (id) => {
    try {
        await axios.delete(`/api/delete-personal-details/${id}`);
        toast.success("Successfully deleted!", {
            autoClose: 2000,
        });

        // Remove the deleted detail from the personalDetails array
        const index = personalDetails.value.findIndex(
            (detail) => detail.id === id
        );

        if (index !== -1) {
            personalDetails.value.splice(index, 1);
            fetchDetails(
                `/api/personal-details/?page=${meta.value.current_page}`
            );
        }
        await router.push({ name: "personal.details" });
    } catch (error) {
        console.error("Error deleting personal detail:", error);
    }
};
</script>

<template>
    <Dashboard />
    <div class="container p-2">
        <div class="card mt-4">
            <div class="card-body p-4">
                <div class="mb-3">
                    <router-link
                        class="btn btn-primary btn-md"
                        :to="'/create-personal-details'"
                        >Create New</router-link
                    >
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead class="text-center">
                            <tr>
                                <th>No.</th>
                                <th>First Name</th>
                                <th>Middle Name</th>
                                <th>Last Name</th>
                                <th>Birthdate</th>
                                <th>Address</th>
                                <th>Gender</th>
                                <th>Contact</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-if="personalDetails.length === 0">
                                <tr>
                                    <td colspan="9" class="text-center">
                                        No Data
                                    </td>
                                </tr>
                            </template>
                            <template v-else>
                                <tr
                                    v-for="(detail, index) in personalDetails"
                                    :key="detail.id"
                                >
                                    <td>
                                        {{
                                            (meta.current_page - 1) *
                                                meta.per_page +
                                            index +
                                            1
                                        }}
                                    </td>
                                    <td>{{ detail.first_name }}</td>
                                    <td>{{ detail.middle_name }}</td>
                                    <td>{{ detail.last_name }}</td>
                                    <td>{{ detail.birthdate }}</td>
                                    <td>{{ detail.address }}</td>
                                    <td>{{ detail.gender }}</td>
                                    <td>{{ detail.contact_no }}</td>
                                    <td>
                                        <router-link
                                            class="btn btn-info btn-sm me-1"
                                            :to="`/edit-personal-details/${detail.id}/${meta.current_page}`"
                                        >
                                            Edit
                                        </router-link>
                                        <button
                                            type="button"
                                            class="btn btn-danger btn-sm"
                                            @click="
                                                () => deleteDetail(detail.id)
                                            "
                                        >
                                            Delete
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                    <nav>
                        <ul class="pagination">
                            <li
                                class="page-item"
                                v-for="page in meta.links"
                                :key="page"
                            >
                                <a
                                    class="page-link"
                                    :class="{
                                        active: page.label == meta.current_page,
                                    }"
                                    @click="fetchDetails(page.url)"
                                    href="#"
                                    v-html="page.label"
                                >
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</template>
