<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";
import { useRouter, useRoute } from "vue-router";
import { toast } from "vue3-toastify";
import "vue3-toastify/dist/index.css";
import Dashboard from "../../Dashboard.vue";

const router = useRouter();
const route = useRoute();
const formData = ref({
    id: null,
    first_name: "",
    middle_name: "",
    last_name: "",
    birthdate: "",
    address: "",
    gender: "",
    contact_no: "",
});

const editRecordPageBelong = ref(route.params?.page || "");

const fetchPersonalDetails = async (id) => {
    try {
        const response = await axios.get(`/api/edit-personal-details/${id}`);
        const personalDetails = response.data;

        // Update formData with fetched data
        Object.assign(formData.value, personalDetails);
    } catch (error) {
        console.error("Error fetching personal details:", error);
    }
};

onMounted(() => {
    const id = route.params.id;
    fetchPersonalDetails(id);
});

const onUpdateDetail = async () => {
    const id = formData.value.id;
    try {
        const response = await axios.put(
            `/api/update-personal-details/${id}`,
            formData.value
        );
        console.log("Updated personal details:", response.data);
        toast.success("Successfully updated.", {
            autoClose: 1000,
        });
    } catch (error) {
        console.error("Error updating personal details:", error);
    }
};
</script>

<template>
    <Dashboard />
    <div class="container mt-4">
        <div class="card">
            <!-- <form @submit.prevent="onUpdateDetail"> -->
            <div class="card-header bg-secondary text-white">
                PERSONAL DETAILS
            </div>
            <div class="card-body p-4">
                <div class="form-group mb-2">
                    <label for="">First Name</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        name="first_name"
                        v-model="formData.first_name"
                    />
                </div>
                <div class="form-group mb-2">
                    <label for="">Middle Name (Optional)</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        name="middle_name"
                        v-model="formData.middle_name"
                    />
                </div>
                <div class="form-group mb-2">
                    <label for="">Last Name</label>
                    <input
                        type="text"
                        class="form-control form-control-sm"
                        name="last_name"
                        v-model="formData.last_name"
                    />
                </div>
                <div class="form-group mb-2">
                    <label for="">Birth Date</label>
                    <input
                        type="date"
                        class="form-control form-control-sm"
                        name="birthdate"
                        v-model="formData.birthdate"
                    />
                </div>
                <div class="form-group mb-2">
                    <label for="">Address</label>
                    <textarea
                        type="text"
                        class="form-control form-control-sm"
                        name="address"
                        v-model="formData.address"
                    >
                    </textarea>
                </div>
                <div class="form-group mb-2">
                    <div>
                        <label for="">Gender</label>
                    </div>
                    <div class="card p-2">
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="gender"
                                value="male"
                                v-model="formData.gender"
                            />
                            <label
                                class="form-check-label"
                                for="flexRadioDefault1"
                                >Male</label
                            >
                        </div>
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="radio"
                                name="gender"
                                value="female"
                                v-model="formData.gender"
                            />
                            <label
                                class="form-check-label"
                                for="flexRadioDefault2"
                                >Female</label
                            >
                        </div>
                    </div>
                </div>
                <div class="form-group mb-3">
                    <label for="">Contact No.</label>
                    <input
                        type="number"
                        class="form-control form-control-sm"
                        name="contact_no"
                        v-model="formData.contact_no"
                    />
                </div>
                <div class="float-end mb-3">
                    <router-link
                        class="btn btn-secondary me-2"
                        :to="`/personal-details?page=${editRecordPageBelong}`"
                        >Lists</router-link
                    >
                    <button
                        type="button"
                        class="btn btn-primary"
                        @click="onUpdateDetail"
                    >
                        Submit
                    </button>
                </div>
            </div>
            <!-- </form> -->
        </div>
    </div>
</template>
