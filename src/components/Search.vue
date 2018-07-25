<template>
    <div>
        <div class="form-group">
            <label>Concours</label>
            <multiselect v-model="conc" :options="concs" :custom-label="customLabelConc" :showLabels="false" :placeholder="'Choisissez un concours'" @input="filterByConc">
                <template slot="beforeList">
                    <li>
                        <table class="table table-sm table-bordered mb-0">
                            <thead>
                            <tr>
                                <th class="codeconc">Code</th>
                                <th class="dtconc">Date</th>
                                <th class="libconc">Libellé</th>
                                <th class="lieuconc">Lieu</th>
                            </tr>
                            </thead>
                        </table>
                    </li>
                </template>
                <template slot="option" slot-scope="props">
                    <table class="table table-sm table-bordered mb-0">
                        <tbody>
                        <tr>
                            <td class="codeconc">{{ props.option.CODECONC }}</td>
                            <td class="dtconc">{{ props.option.DTCONC }}</td>
                            <td class="libconc">{{ props.option.LIBCONC }}</td>
                            <td class="lieuconc">{{ props.option.LIEUCONC }}</td>
                        </tr>
                        </tbody>
                    </table>
                </template>
            </multiselect>
        </div>

        <div class="form-group">
            <label>Animal</label>
            <multiselect v-model="animal" :options="animalsConc" :custom-label="customLabelAnimal" :showLabels="false" :placeholder="'Choisissez un animal'" :id="'animal'">
                <template slot="singleLabel" slot-scope="props">{{ boucleFormatted(props.option) }}</template>
                <template slot="beforeList">
                    <li>
                        <table class="table table-sm table-bordered mb-0">
                            <thead>
                            <tr>
                                <th class="btrav">Btrav</th>
                                <th class="bsaumon">Boucle</th>
                                <th class="noman">Nom</th>
                                <th class="noordre">N° ordre</th>
                            </tr>
                            </thead>
                        </table>
                    </li>
                </template>
                <template slot="option" slot-scope="props">
                    <table class="table table-sm table-bordered mb-0">
                        <tbody>
                        <tr>
                            <td class="btrav">{{ props.option.BTRAV }}</td>
                            <td class="bsaumon">{{ boucleFormatted(props.option) }}</td>
                            <td class="noman">{{ props.option.NOMAN }}</td>
                            <td class="noordre">{{ props.option.NOORDRE }}</td>
                        </tr>
                        </tbody>
                    </table>
                </template>
            </multiselect>
        </div>

        <table class="table table-bordered" v-if="animal">
            <tr>
                <td>N° ordre</td>
                <td>
                    <strong>{{ animal.NOORDRE }}</strong>
                </td>
            </tr>
            <tr>
                <td>Boucle</td>
                <td>{{ boucleFormatted(animal) }}</td>
            </tr>
            <tr>
                <td>Nom</td>
                <td>{{ animal.NOMAN }}</td>
            </tr>
            <tr>
                <td>Père</td>
                <td>{{ animal.PERE }}</td>
            </tr>
            <tr>
                <td>Mère</td>
                <td>{{ animal.MERE }}</td>
            </tr>
        </table>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
import _ from 'lodash'
import $ from 'jquery'

export default {
  name: 'Search',
  components: {Multiselect},
  data () {
    return {
      conc: null,
      concs: [],
      animal: null,
      animals: [],
      animalsConc: []
    }
  },
  mounted () {
    // let exportUrl = 'http://localhost:8080/export.json'
    // let exportUrl = 'http://localhost/concinsc/export.php'
    let exportUrl = '/export.json'
    $.get(exportUrl).done(data => {
      this.$localforage.setItem('animals', data).then(() => {
        this.$localforage.getItem('animals').then(value => {
          this.animals = value
            this.concs = _.uniqBy(value, 'CODECONC')
          }).catch(function (error) {
            window.console.error(error)
          })
      }, () => {
        alert('Erreur lors du chargement des données')
      })
    })

    document.getElementById('animal').type = 'number'
  },
  methods: {
    customLabelAnimal (animal) {
      return animal.BTRAV
    },
    customLabelConc (animal) {
      return animal.CODECONC
    },
    boucleFormatted (animal) {
      return `${animal.BSAUMON_PAYS}${animal.BSAUMON}`
    },
    filterByConc (event) {
      this.animal = null
      this.animalsConc = []
      if (event.CODECONC) {
        this.animalsConc = _.filter(this.animals, {CODECONC: event.CODECONC})
      }
    }
  }
}
</script>

<style lang="scss">
    .multiselect {
        .multiselect__content-wrapper {
            .multiselect__content {
                .multiselect__element {
                    .multiselect__option {
                        padding: 0;
                        min-height: auto;
                    }
                }

                table {
                    tr {
                        td, th {
                            &.codeconc {
                                width: 15%;
                            }

                            &.dtconc {
                                width: 15%;
                            }

                            &.libconc {
                                width: 40%;
                            }

                            &.lieuconc {
                                width: 30%;
                            }
                        }
                    }
                }

                table {
                    tr {
                        td, th {
                            &.btrav {
                                width: 20%;
                            }

                            &.bsaumon {
                                width: 30%;
                            }

                            &.noman {
                                width: 35%;
                            }

                            &.noordre {
                                width: 15%;
                            }

                        }
                    }
                }
            }
        }
    }
</style>
