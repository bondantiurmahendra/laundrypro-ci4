console.log("Halo DUnia!!!");
const email = {
	createdPesanan: async (dataPesanan) => {
		const res = await fetch("/send-email", {
			method: "POST",
			headers: {
				"Content-Type": "application/json",
			},
			body: JSON.stringify({
				to: "jackkolor69@gmail.com",
				subject: "Pesanan Baru Masuk",
				view: "emails/OrderCreated",
				viewData: dataPesanan,
			}),
		});
		const json = await res.json();
		return json;
	},
};
