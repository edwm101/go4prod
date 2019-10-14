<template>
  <div>
    <v-card max-width="400">
      <v-card-title class="title font-weight-regular justify-space-between">
        <span>Heureux de vous revoir !</span>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <v-form ref="form" v-model="valid">
          <v-layout row wrap px-2>
            <v-flex xs12>
              <v-text-field
                outlined
                label="Email"
                v-model="email"
                :rules="[rules.required,rules.email]"
              ></v-text-field>
            </v-flex>
            <v-flex xs12>
              <v-text-field
                 outlined
                :append-icon="show_password ? 'mdi-eye' : 'mdi-eye-off'"
                :rules="[rules.required,rules.min,rules.lowerCase,rules.upperCase,rules.number,rules.regexp]"
                :type="show_password ? 'text' : 'password'"
                v-model="password"
                label="Mot de passe"
                @click:append="show_password = !show_password"
              ></v-text-field>
            </v-flex>
            <v-flex xs12></v-flex>
          </v-layout>
        </v-form>
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-btn  :to="{name : 'signup'}" text >S'inscrire</v-btn>

        <v-spacer></v-spacer>
        <v-btn to="/user/my-resto" color="primary" @click="signin" :disabled="!valid">Connexion</v-btn>
      </v-card-actions>
    </v-card>

    <v-dialog v-model="dialogWait" width="300">
      <v-card color="primary" dark>
        <v-card-text>
          Attendez que l'authentification soit terminée ...
          <v-progress-linear indeterminate color="white" class="mb-0"></v-progress-linear>
        </v-card-text>
      </v-card>
    </v-dialog>
  </div>
</template>


<script>
import { load as loadRecaptcha } from "recaptcha-v3";

export default {
  data: () => ({
    valid:false,
    email: "",
    password: "",
    step: 1,
    show_password: false,
    is_valid: false,
    rules: {
      required: value => !!value || "Requis *",
      min: v => v.length >= 8 || "Au moins 8 caractères",
      lowerCase: value =>
        value.toUpperCase() != value || "Une lettre minuscule",
      upperCase: value =>
        value.toLowerCase() != value || "Une lettre majuscule",
      number: value => /\d/.test(value) || "Un chiffre",
      regexp: value =>
        !/^[a-zA-Z0-9]+$/.test(value) ||
        "Un symbole (_ ? ! € $ £ @ : + = - ou autres à définir)",
      email: value =>
        /^[a-zA-Z0-9-_.]+@[a-zA-Z0-9-_]+\.[a-zA-Z0-9\-_. ]+$/.test(value) ||
        "Email invalide."
    },
    dialogWait: false
  }),

  computed: {
    form() {
      return {
        email: this.email,
        password: this.password
      };
    }
  },
  methods: {
    async signin() {
      if (this.$refs.form.validate()) {
        this.dialogWait = true;

        const recaptcha = await loadRecaptcha(
          "6LcVpq0UAAAAAPjaJAr01dnOzYcJX5z7IBsLzR78"
        );
        const recaptchaToken = await recaptcha.execute();

        await this.$store.dispatch("userSignIn", {
          ...this.form,
          ...{ recaptchaToken }
        });
        this.dialogWait = false;
      }
    }
  }
};
</script>