const { Button, Input, SectionHeading } = window.BCAPartnersDesignSystem_f92865;
const AC = "../../assets/";

function Contact() {
  const [sent, setSent] = React.useState(false);
  return (
    <div>
      <section style={{ position: "relative", height: 260, overflow: "hidden", display: "flex", alignItems: "center", padding: "0 112px", backgroundImage: `linear-gradient(rgba(0,121,166,0.85),rgba(0,121,166,0.85)), url(${AC}contact-bg.jpg)`, backgroundSize: "cover", backgroundPosition: "center" }}>
        <div>
          <h1 style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 48, lineHeight: "56px", letterSpacing: "-0.025em", color: "#fff" }}>How can we help you succeed?</h1>
          <p style={{ margin: "12px 0 0", fontFamily: "var(--font-sans)", fontSize: 18, lineHeight: "28px", color: "rgba(255,255,255,0.9)" }}>Tell us about your business challenge — we'll get back within one business day.</p>
        </div>
      </section>
      <section style={{ padding: "80px 112px", background: "#fff", display: "grid", gridTemplateColumns: "1fr 380px", gap: 80 }}>
        <div style={{ maxWidth: 620 }}>
          <div style={{ marginBottom: 32 }}><SectionHeading align="left" color="var(--bca-navy)">Get in touch</SectionHeading></div>
          {sent ? (
            <div style={{ padding: 32, borderRadius: 4, background: "var(--bca-surface-section)", fontFamily: "var(--font-sans)", fontSize: 18, color: "var(--bca-navy)", fontWeight: 600 }}>
              Thank you — your message has been sent. We'll be in touch shortly.
            </div>
          ) : (
            <form onSubmit={(e) => { e.preventDefault(); setSent(true); }} style={{ display: "flex", flexDirection: "column", gap: 20 }}>
              <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 20 }}>
                <Input label="Full name" placeholder="Your name" required />
                <Input label="Company" placeholder="Company name" />
              </div>
              <div style={{ display: "grid", gridTemplateColumns: "1fr 1fr", gap: 20 }}>
                <Input label="Email" type="email" placeholder="you@company.com" required />
                <Input label="Phone" placeholder="+84 …" />
              </div>
              <Input label="How can we help?" multiline rows={5} placeholder="Describe your business challenge…" />
              <div><Button variant="filled" icon as="button">Send message</Button></div>
            </form>
          )}
        </div>
        <aside style={{ paddingTop: 8 }}>
          <h3 style={{ margin: "0 0 16px", fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 20, color: "var(--bca-navy)" }}>BCA Partners</h3>
          <p style={{ margin: 0, fontFamily: "var(--font-sans)", fontSize: 16, lineHeight: "26px", color: "var(--bca-ink-2)", whiteSpace: "pre-line" }}>{"Unit G2, FOSCO Building\n06 Phung Khac Khoan\nDakao Ward, District 1, HCMC, Vietnam\n\nTel: +84 2835124414\nEmail: info@bcapartners.com.vn"}</p>
        </aside>
      </section>
    </div>
  );
}
window.Contact = Contact;
