const btnAgregarRegistros = document.getElementById('agregar-registros');
btnAgregarRegistros.addEventListener('click', async function (params) {
    let response = await axios.get(`${global_api}scriptTable.php`);
    if(response.data.response === 'success') {
        await Swal.fire('¡Excelente!', 'Datos agregados', 'success')
        location.reload();
    }
});