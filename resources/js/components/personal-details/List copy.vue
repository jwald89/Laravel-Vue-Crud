<script setup>
import { ref, reactive, onMounted } from "vue";
import axios from "axios";
import { useRouter } from "vue-router";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";

const router = useRouter();

const personalDetails = reactive([]);

const fetchDetails = async () => {
    try {
        const response = await axios.get("/api/personal-details");
        personalDetails.push(...response.data);
    } catch (error) {
        console.error("Error fetching personal details:", error);
    }
};

onMounted(fetchDetails);

const deleteDetail = async (id) => {
    try {
        await axios.delete(`/api/delete-personal-details/${id}`);
        toast.success("Successfully deleted!", {
            autoClose: 2000,
        });

        // Remove the deleted detail from the personalDetails array
        const index = personalDetails.findIndex((detail) => detail.id === id);
        if (index !== -1) {
            personalDetails.splice(index, 1);
        }
        await router.push({ name: "personal.details" });
    } catch (error) {
        console.error("Error deleting personal detail:", error);
    }
};
</script>

<template>
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
                <div class="table-response">
                    <table class="table">
                        <thead class="text-center">
                            <tr>
                                <TH>No.</TH>
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
                        <tbody class="text-center">
                            <tr
                                v-for="(detail, index) in personalDetails"
                                :key="index"
                            >
                                <td>{{ index + 1 }}</td>
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
                                        :to="`/edit-personal-details/${detail.id}`"
                                    >
                                        Edit
                                    </router-link>
                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm"
                                        @click="() => deleteDetail(detail.id)"
                                    >
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>
