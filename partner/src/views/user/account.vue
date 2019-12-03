<template>
  <div>
    <v-toolbar class="toolbar-simple">
      <v-toolbar-title left>
        Modifier les informations de votre compte
      </v-toolbar-title>

      <v-spacer></v-spacer>
    </v-toolbar>

    <v-divider></v-divider>
    <v-container fluid>
      <v-layout wrap class="pa-2">
        <v-flex xs12 sm6 md4>
          <v-card class="elevation-1">
            <v-toolbar class="elevation-0" dense>
              <v-toolbar-title>Mon profil</v-toolbar-title>
            </v-toolbar>
            <v-divider></v-divider>
            <v-card-text>
              <v-form ref="form">
                <v-layout row wrap>
                  <v-flex xs6 class="pa-1">
                    <v-text-field label="Prénom" v-model="first_name" outlined></v-text-field>
                  </v-flex>
                  <v-flex xs6 class="pa-1">
                    <v-text-field label="Nom" v-model="last_name" outlined></v-text-field>
                  </v-flex>
                  <v-flex xs12 class="pa-1">
                    <v-text-field
                      outlined
                      label="Numéro de téléphone"
                      value="33 0 23 12 33 23"
                      mask="+33 # ## ## ## ##"
                      prepend-inner-icon="mdi-phone"
                    ></v-text-field>
                  </v-flex>

                  <v-flex xs12 class="pa-1">
                    <v-text-field label="Adresse" outlined> </v-text-field>
                  </v-flex>
                  <v-flex xs6 class="pa-1">
                    <v-text-field label="Ville" outlined></v-text-field>
                  </v-flex>
                  <v-flex xs6 class="pa-1">
                    <v-text-field mask="#####" outlined label="Code postal"></v-text-field>
                  </v-flex>
                </v-layout>
              </v-form>
            </v-card-text>

            <v-divider></v-divider>

            <v-card-actions>
              <v-spacer></v-spacer>
              <v-btn class="primary" text>
                Enregistrer
                <v-icon right>mdi-content-save</v-icon>
              </v-btn>
            </v-card-actions>
          </v-card>
        </v-flex>
      </v-layout>
    </v-container>
  </div>
</template>


<script>
import { mapGetters } from "vuex";

export default {
  data: () => ({}),
  computed: {
    ...mapGetters(["first_name", "last_name", "email"])
  }
};
</script>