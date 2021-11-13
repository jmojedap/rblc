<div class="row">
    <div class="col-md-5">
        <h3>Resumen</h3>
        <table class="table bg-white">
            <tbody>
                <tr>
                    <td>Total professionals</td>
                    <td class="text-center">{{ summary.qty_professionals }}</td>
                    <td class="text-center">100%</td>
                </tr>
                <tr>
                    <td>Total professionals invitados</td>
                    <td class="text-center">{{ summary.qty_invited_users }}</td>
                    <td class="text-center">
                        {{ Pcrn.intPercent(summary.qty_invited_users, summary.qty_professionals) }} %
                    </td>
                </tr>
                <tr>
                    <td>Total professionals activos</td>
                    <td class="text-center">{{ summary.qty_actived_professionals }}</td>
                    <td class="text-center">
                        {{ Pcrn.intPercent(summary.qty_actived_professionals, summary.qty_professionals) }} %
                    </td>
                </tr>
                <tr>
                    <td>Cantidad invitaciones</td>
                    <td class="text-center">{{ summary.qty_invitations }}</td>
                    <td class="text-center"></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="col-md-7">
        <h3>Por día</h3>
    </div>
</div>