new Vue({ });
var app = new Vue({
    el : '#app',
    methods : {
        pesan: function() {
            alert("Selamat belajar Vue.js")
        },
        simpan: function () {
            this.dataGuru.push(this.guru);
            this.guru = "";
        }
    },
    data: {
        'dataGuru' : [
                        {'nama' : 'Arya', 'kelas' : 'XRPL1'},
                        {'nama' : 'Bella', 'kelas' : 'XRPL2'},
                        {'nama' : 'Cintya', 'kelas' : 'XIRPL1'},
                        {'nama' : 'Doni', 'kelas' : 'XIRPL2'},
                        {'nama' : 'Elma', 'kelas' : 'XIIRPL1'},
                        {'nama' : 'Farhan', 'kelas' : 'XIIRPL2'},
        ],
    'guru' : ''
    }
});
