const { LeaderCard, SectionHeading } = window.BCAPartnersDesignSystem_f92865;
const AL = "../../assets/";

const MGMT = [
  { photo: AL + "leader-binh.jpg", name: "Binh Pham, MBA, CFA", role: "Managing Director" },
  { photo: AL + "leader-thuy.png", name: "Thuy Huynh, PhD", role: "Director, Operations & Transformation" },
  { photo: AL + "leader-chau.png", name: "Chau Tran, Msc", role: "Director, Finance & Human Capitals" },
];
const ADVISORS = [
  { photo: AL + "leader-andy.png", name: "Andy Phan, PhD, MBA", role: "Advisor, Financial Market & Strategy" },
  { photo: AL + "leader-nga.png", name: "Nga Do, MBA", role: "Advisor, Risk Management & Strategy" },
];

function LeaderHero() {
  return (
    <section style={{ position: "relative", height: 524, overflow: "hidden" }}>
      <img src={AL + "leadership-hero.jpg"} alt="" style={{ position: "absolute", inset: 0, width: "100%", height: "100%", objectFit: "cover" }} />
      <div style={{ position: "absolute", inset: 0, background: "rgba(0,0,0,0.35)" }} />
      <div style={{ position: "absolute", left: 108, top: 176, width: 648, display: "flex", flexDirection: "column", gap: 16 }}>
        <h1 style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 60, lineHeight: "72px", letterSpacing: "-0.025em", color: "#fff" }}>Our Leadership</h1>
        <p style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 400, fontSize: 18, lineHeight: "28px", color: "#fff" }}>People are our most valuable asset. We recruit and develop our team from different backgrounds and experiences so that we can deliver the right team, with the right experience and expertise to our client.</p>
      </div>
    </section>
  );
}

function LeaderGroup({ title, people }) {
  return (
    <div style={{ marginBottom: 64 }}>
      <div style={{ marginBottom: 32 }}><SectionHeading align="left">{title}</SectionHeading></div>
      <div style={{ display: "grid", gridTemplateColumns: "repeat(3, 1fr)", gap: 32 }}>
        {people.map((p) => <LeaderCard key={p.name} {...p} style={{ width: "100%" }} />)}
      </div>
    </div>
  );
}

function Leadership() {
  return (
    <div>
      <LeaderHero />
      <section style={{ padding: "80px 112px", background: "#fff" }}>
        <LeaderGroup title="Management Team" people={MGMT} />
        <LeaderGroup title="Advisors" people={ADVISORS} />
      </section>
    </div>
  );
}
window.Leadership = Leadership;
