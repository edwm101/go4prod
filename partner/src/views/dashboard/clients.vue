<template>
  <v-stepper v-model="e1" class="elevation-0 transparent">
    <v-stepper-header class="elevation-0">
      <v-stepper-step :complete="e1 > 1" step="1">Données produit</v-stepper-step>

      <v-divider></v-divider>

      <v-stepper-step :complete="e1 > 2" step="2">Images</v-stepper-step>

      <v-divider></v-divider>

      <v-stepper-step step="3">Validation</v-stepper-step>
    </v-stepper-header>
    <v-divider></v-divider>

    <v-stepper-items>
      <v-stepper-content step="1">
        <v-form>
          <v-container>
            <v-layout row wrap>
              <v-flex xs12 sm6>
                <v-text-field
                  v-model="title"
                  :rules="[rules.required, rules.counterTitle]"
                  maxlength="50"
                  counter
                  label="Titre"
                  solo
                ></v-text-field>
                <v-textarea
                  v-model="description"
                  :rules="[rules.required, rules.counterDescription]"
                  label="Description"
                  counter
                  maxlength="200"
                  solo
                ></v-textarea>
              </v-flex>
            </v-layout>
          </v-container>
        </v-form>

        <v-divider></v-divider>
        <v-btn color="primary" @click="e1 = 2">Continue</v-btn>
        <v-btn color="success" @click="e1 = 2">Save</v-btn>
      </v-stepper-content>

      <v-stepper-content step="2">
        <v-divider></v-divider>
        <v-btn color="primary" @click="e1 = 1">Go Back</v-btn>
        <v-btn color="primary" @click="e1 = 3">Continue</v-btn>
      </v-stepper-content>

      <v-stepper-content step="3">
        <v-btn color="primary" @click="e1 = 2">Go Back</v-btn>
        <v-btn color="success" @click="onValid">Save</v-btn>

        <v-btn text router to="/">Cancel</v-btn>
      </v-stepper-content>
    </v-stepper-items>
  </v-stepper>
</template>

<script>
export default {
  data() {
    return {
      e1: 0,
      title: "",
      description: "",
      rules: {
        required: value => !!value || "Required.",
        counterTitle: value => value.length <= 50 || "Max 50 characters",
        counterDescription: value => value.length <= 200 || "Max 200 characters"
      }
    };
  }
};
</script>