function cerrarPeriodo(idContrato, idPeriodo) {
    if (confirm('¿Está seguro que desea cerrar este período?')) {
        const url = `${window.location.origin}${BASE_URL}contratos/${idContrato}/cerrar-periodo/${idPeriodo}`;
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Error al cerrar el período');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                alert('Período cerrado exitosamente');
                window.location.reload();
            } else {
                alert(data.message || 'Error al cerrar el período');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al cerrar el período. Por favor, intente nuevamente.');
        });
    }
}