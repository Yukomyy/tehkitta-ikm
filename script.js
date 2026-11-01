
const roles = {
    superadmin: {
        label:'Super Admin', color:'#2da042',
        desc:'Akses penuh ke seluruh fitur sistem termasuk manajemen user & role.',
        can:['kat','prd','sup','trx','users','roles'],
        canAdd:['kat','prd','sup','trx','user'],
        canEdit:true, canDel:true
    },
    // ... (sisa konfigurasi role lainnya sama persis seperti file asli)
    viewer: {
        label:'Viewer', color:'#718096',
        desc:'Hanya dapat melihat data, tidak dapat menambah, mengedit, atau menghapus.',
        can:['kat','prd','sup','trx'],
        canAdd:[], canEdit:false, canDel:false
    }
};
let currentRole = 'superadmin';