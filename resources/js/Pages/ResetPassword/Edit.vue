<template>
    <Head>
        <title>Reset password - {{ $page.props.app_name }}</title>
    </Head>
    <CenterScreen>
        <Form
            class="w-full p-4 sm:w-96"
            @submit.prevent="form.post('/reset-password')"
        >
            <SuccessFlash v-if="flash?.success">
                {{ flash.success }}
            </SuccessFlash>
            <template v-else>
                <Header>Reset password</Header>
                <input type="hidden" v-model="form.token" />
                <input type="hidden" v-model="form.email" />
                <ErrorFeedback v-if="form.errors.email">
                    {{ form.errors.email }}
                </ErrorFeedback>
                <InputGroup>
                    <label for="password">Password</label>
                    <Input
                        type="password"
                        id="password"
                        v-model="form.password"
                        required
                    />
                    <ErrorFeedback v-if="form.errors.password">
                        {{ form.errors.password }}
                    </ErrorFeedback>
                </InputGroup>
                <InputGroup>
                    <label for="password_confirmation">Confirm password</label>
                    <Input
                        type="password"
                        id="password_confirmation"
                        v-model="form.password_confirmation"
                        required
                    />
                    <ErrorFeedback v-if="form.errors.password_confirmation">
                        {{ form.errors.password_confirmation }}
                    </ErrorFeedback>
                </InputGroup>
                <BlueButton type="submit" :disabled="form.processing">
                    Reset
                </BlueButton>
            </template>
        </Form>
    </CenterScreen>
</template>

<script setup>
import { Head } from "@inertiajs/vue3";
import { useForm } from "@inertiajs/vue3";
import CenterScreen from "../../Components/CenterScreen.vue";
import Form from "../../Components/Form/Form.vue";
import SuccessFlash from "../../Components/Flash/SuccessFlash.vue";
import Header from "../../Components/Form/Header.vue";
import InputGroup from "../../Components/Form/InputGroup.vue";
import Input from "../../Components/Form/Input.vue";
import ErrorFeedback from "../../Components/Form/Feedback/ErrorFeedback.vue";
import BlueButton from "../../Components/Form/Buttons/BlueButton.vue";

const props = defineProps({
    token: String,
    email: String,
    flash: Object,
});

const form = useForm({
    token: props.token,
    email: props.email,
    password: null,
    password_confirmation: null,
});
</script>
