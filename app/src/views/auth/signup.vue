<template>
  <div>
    <v-card max-width="400">
      <v-card-title class="title font-weight-regular justify-space-between">
        <span>S'inscrire</span>
      </v-card-title>
      <v-divider></v-divider>
      <v-card-text>
        <v-form ref="form"  v-model="valid">
          <v-layout row wrap px-2>
            <v-flex xs6 class="pa-1">
              <v-text-field outlined label="Prénom" v-model="first_name" :rules="[rules.required]"></v-text-field>
            </v-flex>
            <v-flex xs6 class="pa-1">
              <v-text-field outlined label="Nom" v-model="last_name" :rules="[rules.required]"></v-text-field>
            </v-flex>
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
                hint="Mot de passe, doit comprendre au moins : <br>
- 8 caractères <br>
- une lettre minuscule <br>
- une lettre majuscule <br>
- un chiffre <br>
- un symbole (_ ? ! € $ £ @ : + = - ou autres à définir)"
                name="input-10-2"
                label="Mot de passe"
                @click:append="show_password = !show_password"
              ></v-text-field>
            </v-flex>
          </v-layout>
        </v-form>
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-btn :to="{name : 'signin'}" text>Vous avez un compte ?</v-btn>

        <v-spacer></v-spacer>
        <v-btn color="primary" @click="signup" :disabled="!valid">Suivant</v-btn>
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
    recaptcha: null,
    first_name: "",
    last_name: "",
    email: "",
    password: "",
    step: 1,
    show_password: false,
    rules: {
      required: v => !!v || "Requis *",
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
        first_name: this.first_name,
        last_name: this.last_name,
        email: this.email,
        password: this.password
      };
    }
  },
  methods: {
    async signup() {
      if (this.$refs.form.validate()) {
        this.dialogWait = true;

        const recaptcha = await loadRecaptcha(
          "6LcVpq0UAAAAAPjaJAr01dnOzYcJX5z7IBsLzR78"
        );
        const recaptchaToken = await recaptcha.execute();

        await this.$store.dispatch("userSignUp", {
          ...this.form,
          ...{ recaptchaToken }
        });

        this.dialogWait = false;
      }
    }
  }
};
</script>