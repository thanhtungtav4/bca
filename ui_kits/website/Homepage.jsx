const { Navbar, Footer, Button, SectionHeading, Service, ServiceCard, ProjectCard, FeatureItem, LeaderCard, ClientLogo, Input } = window.BCAPartnersDesignSystem_f92865;
const A = "../../assets/";

/* 1 — Hero (left-aligned) */
function Hero({ onNav }) {
  return (
    <section style={{ position: "relative", height: 620, overflow: "hidden", background: "#dfe3ea" }}>
      <img src={A + "hero.jpg"} alt="" style={{ position: "absolute", inset: 0, width: "100%", height: "100%", objectFit: "cover", objectPosition: "70% center" }} />
      <div style={{ position: "absolute", inset: 0, background: "linear-gradient(90deg, rgba(0,26,46,0.82) 0%, rgba(0,26,46,0.5) 42%, rgba(0,26,46,0) 72%)" }} />
      <div style={{ position: "absolute", left: 112, top: 170, width: 560, display: "flex", flexDirection: "column", gap: 16 }}>
        <h1 style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 800, fontSize: 59, lineHeight: "100%", letterSpacing: "-0.025em", color: "#fff" }}>Fostering Business Evolution</h1>
        <p style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 400, fontSize: 18, lineHeight: "28px", color: "rgba(255,255,255,0.92)", maxWidth: 440 }}>Trusted partner for your most important business challenges — from M&amp;A to market entry across Vietnam.</p>
        <div style={{ marginTop: 24 }}>
          <Button variant="filled" onClick={() => onNav("Contact us")}>Contact Us</Button>
        </div>
      </div>
    </section>
  );
}

/* 2 — Insights (horizontal card row) */
const INSIGHTS = [
  { image: A + "feature-fintech.jpg", title: "Identifying the right partner to enter Vietnam Fintech", body: "The future of payment in Fintech is fostered by the high rate of internet usage in Vietnam." },
  { image: A + "feature-lease.jpg", title: "Vietnam operating lease market", body: "A nascent phase with little to no regulatory barriers to entry — an opening for early movers." },
  { image: A + "feature-msme.jpg", title: "MSMEs and access to capital", body: "Micro, small and medium enterprises in Vietnam still face real difficulties accessing bank loans." },
  { image: A + "project-dragonfruit.jpg", title: "Restructuring agricultural value chains", body: "Rethinking logistics, storage and financing for Vietnam's staple export crops." },
];
function InsightCard({ image, title, body }) {
  return (
    <article style={{ width: 384, flexShrink: 0, display: "flex", flexDirection: "column", gap: 16 }}>
      <FeatureItem image={image} height={220} />
      <h3 style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 20, lineHeight: "28px", color: "var(--bca-ink)" }}>{title}</h3>
      <p style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 400, fontSize: 16, lineHeight: "24px", color: "var(--bca-ink-2)" }}>{body}</p>
    </article>
  );
}
function Insights() {
  return (
    <section style={{ padding: "80px 0", background: "#fff" }}>
      <div style={{ textAlign: "center", marginBottom: 48 }}><SectionHeading>Insights</SectionHeading></div>
      <div style={{ display: "flex", gap: 32, overflowX: "auto", padding: "0 112px 8px" }}>
        {INSIGHTS.map((i) => <InsightCard key={i.title} {...i} />)}
      </div>
      <div style={{ display: "flex", justifyContent: "center", marginTop: 48 }}><Button variant="filled">Explore more</Button></div>
    </section>
  );
}

/* 3 — About Us (image left, About/Mission/Value right) */
function About({ onNav }) {
  return (
    <section style={{ padding: "80px 112px", background: "#fff", display: "grid", gridTemplateColumns: "1fr 1fr", gap: 80, alignItems: "start" }}>
      <div style={{ borderRadius: 4, overflow: "hidden", aspectRatio: "1 / 0.85", background: "var(--bca-border)" }}>
        <img src={A + "about-strip.jpg"} alt="" style={{ width: "100%", height: "100%", objectFit: "cover" }} />
      </div>
      <div style={{ display: "flex", flexDirection: "column", gap: 24 }}>
        <div style={{ display: "flex", flexDirection: "column", gap: 12 }}>
          <SectionHeading align="left">About Us</SectionHeading>
          <p style={{ margin: 0, fontFamily: "var(--font-sans)", fontSize: 16, lineHeight: "24px", color: "var(--bca-ink-2)" }}>BCA Partners is a consulting firm specializing in M&amp;As, Strategy, Corporate Restructuring and Market Entry in Vietnam. We deliver value through practical solutions and the best service experience.</p>
        </div>
        <div>
          <h3 style={{ margin: "0 0 6px", fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 20, color: "var(--bca-ink)" }}>Mission</h3>
          <p style={{ margin: 0, fontFamily: "var(--font-sans)", fontSize: 16, lineHeight: "24px", color: "var(--bca-ink-2)" }}>To be the trusted local partner that helps businesses navigate and grow in the Vietnamese market.</p>
        </div>
        <div>
          <h3 style={{ margin: "0 0 6px", fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 20, color: "var(--bca-ink)" }}>Value</h3>
          <p style={{ margin: 0, fontFamily: "var(--font-sans)", fontSize: 16, lineHeight: "24px", color: "var(--bca-ink-2)" }}>Integrity, practical insight, and a relentless focus on our clients' outcomes.</p>
        </div>
        <div><Button variant="filled" icon onClick={() => onNav("Our team")}>Explore more</Button></div>
      </div>
    </section>
  );
}

/* 4 — Our Service (6 tiles, one outlined "Explore") */
const SERVICES = [
  { title: "Mergers & Acquisitions", image: A + "service-strategy.jpg" },
  { title: "Strategy", image: A + "service-market-entry.jpg" },
  { title: "Market Entry", image: A + "service-research.jpg" },
  { title: "Corporate Restructuring", image: A + "service-restructuring.jpg" },
  { title: "Capital Raising", image: A + "service-capital.jpg" },
];
function OutlinedService() {
  return (
    <div style={{ border: "1px solid var(--bca-border)", borderRadius: 4, padding: 32, display: "flex", flexDirection: "column", justifyContent: "space-between", minHeight: 257, boxSizing: "border-box" }}>
      <div>
        <h3 style={{ margin: "0 0 12px", fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 24, lineHeight: "100%", color: "var(--bca-ink)" }}>Research</h3>
        <p style={{ margin: 0, fontFamily: "var(--font-sans)", fontSize: 16, lineHeight: "24px", color: "var(--bca-ink-2)" }}>Market and industry research grounded in local data and on-the-ground insight.</p>
      </div>
      <Button variant="ghost" icon size="sm">Explore</Button>
    </div>
  );
}
function Services() {
  return (
    <section style={{ padding: "80px 112px", background: "#fff" }}>
      <div style={{ textAlign: "center", marginBottom: 48 }}><SectionHeading>Our Service</SectionHeading></div>
      <div style={{ display: "grid", gridTemplateColumns: "repeat(3, 1fr)", gap: 32 }}>
        <Service image={SERVICES[0].image} title={SERVICES[0].title} />
        <ServiceCard image={SERVICES[1].image} title={SERVICES[1].title} />
        <ServiceCard image={SERVICES[2].image} title={SERVICES[2].title} />
        <OutlinedService />
        <ServiceCard image={SERVICES[3].image} title={SERVICES[3].title} />
        <ServiceCard image={SERVICES[4].image} title={SERVICES[4].title} />
      </div>
    </section>
  );
}

/* 5 — Key Partners */
const PARTNERS = [A + "partner-1.png", A + "partner-2.png", A + "partner-3.png", A + "partner-4.png", A + "partner-5.png"];
function Partners() {
  return (
    <section style={{ padding: "80px 112px", background: "#fff" }}>
      <div style={{ textAlign: "center", marginBottom: 48 }}><SectionHeading>Key Partners</SectionHeading></div>
      <div style={{ display: "flex", gap: 32, justifyContent: "center", flexWrap: "wrap" }}>
        {PARTNERS.map((p, i) => <ClientLogo key={i} logo={p} width={160} height={64} />)}
      </div>
    </section>
  );
}

/* 6 — Highlighted projects (1 big left + 2 stacked right) */
function BigProject() {
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 24 }}>
      <FeatureItem image={A + "project-strategy.jpg"} height={320} />
      <div style={{ display: "flex", flexDirection: "column", gap: 12 }}>
        <h3 style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 24, color: "var(--bca-ink)" }}>Strategy consulting &amp; Restructuring</h3>
        <p style={{ margin: 0, fontFamily: "var(--font-sans)", fontSize: 16, lineHeight: "24px", color: "var(--bca-ink-2)" }}>As an independent consultant, BCA Partners assisted an e-wallet client across key areas of the business — from strategy to implementation support.</p>
        <Button variant="ghost" icon size="sm">Explore</Button>
      </div>
    </div>
  );
}
function SmallProject({ image, title, body }) {
  return (
    <div style={{ display: "flex", gap: 24, background: "var(--bca-surface-section)", borderRadius: 4, overflow: "hidden" }}>
      <img src={image} alt="" style={{ width: 200, flexShrink: 0, objectFit: "cover" }} />
      <div style={{ padding: "24px 24px 24px 0", display: "flex", flexDirection: "column", gap: 8 }}>
        <h4 style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 18, color: "var(--bca-ink)" }}>{title}</h4>
        <p style={{ margin: 0, fontFamily: "var(--font-sans)", fontSize: 15, lineHeight: "22px", color: "var(--bca-ink-2)" }}>{body}</p>
        <Button variant="ghost" icon size="sm">Explore</Button>
      </div>
    </div>
  );
}
function Highlights() {
  return (
    <section style={{ padding: "40px 112px 80px", background: "#fff", display: "grid", gridTemplateColumns: "1fr 1fr", gap: 32, alignItems: "start" }}>
      <BigProject />
      <div style={{ display: "flex", flexDirection: "column", gap: 24 }}>
        <SmallProject image={A + "project-dragonfruit.jpg"} title="Dragon fruit value chain" body="Restructuring a national staple-fruit chain facing high logistics and storage costs." />
        <SmallProject image={A + "service-market-entry.jpg"} title="Vietnam market entry advisory" body="Regulatory landscape and go-to-market for an international payments leader." />
      </div>
    </section>
  );
}

/* 7 — Meet our team */
const TEAM = [
  { photo: A + "leader-binh.jpg", name: "Binh Pham, MBA, CFA", role: "Managing Director" },
  { photo: A + "leader-thuy.png", name: "Thuy Huynh, PhD", role: "Director, Operations & Transformation" },
  { photo: A + "leader-chau2.jpg", name: "Chau Tran, Msc", role: "Director, Finance & Human Capital" },
  { photo: A + "leader-andy.png", name: "Andy Phan, PhD, MBA", role: "Advisor, Financial Market & Strategy" },
];
function Team({ onNav }) {
  return (
    <section style={{ padding: "80px 112px", background: "#fff", textAlign: "center" }}>
      <div style={{ marginBottom: 48 }}><SectionHeading>Meet our team</SectionHeading></div>
      <div style={{ display: "grid", gridTemplateColumns: "repeat(4, 1fr)", gap: 32 }}>
        {TEAM.map((p) => <LeaderCard key={p.name} {...p} style={{ width: "100%" }} />)}
      </div>
      <div style={{ display: "flex", justifyContent: "center", marginTop: 48 }}>
        <Button variant="filled" icon onClick={() => onNav("Our team")}>See Our Experts</Button>
      </div>
    </section>
  );
}

/* 8 — Contact bar (heading + email + Submit) */
function ContactBar() {
  const [sent, setSent] = React.useState(false);
  return (
    <section style={{ padding: "0 112px 80px", background: "#fff" }}>
      <div style={{ background: "var(--bca-surface-section)", borderRadius: 4, padding: 48, display: "grid", gridTemplateColumns: "1fr auto", gap: 40, alignItems: "center" }}>
        <div>
          <h3 style={{ margin: "0 0 8px", fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 24, color: "var(--bca-navy)" }}>How can we help you succeed?</h3>
          <p style={{ margin: 0, fontFamily: "var(--font-sans)", fontSize: 16, lineHeight: "24px", color: "var(--bca-ink-2)" }}>Leave your email and our team will reach out within one business day.</p>
        </div>
        <form onSubmit={(e) => { e.preventDefault(); setSent(true); }} style={{ display: "flex", gap: 12, alignItems: "center" }}>
          {sent ? (
            <span style={{ fontFamily: "var(--font-sans)", fontWeight: 600, color: "var(--bca-navy)" }}>Thanks — we'll be in touch.</span>
          ) : (
            <React.Fragment>
              <input type="email" required placeholder="you@company.com" style={{ width: 320, fontFamily: "var(--font-sans)", fontSize: 16, padding: "14px 16px", border: "1px solid var(--bca-border)", borderRadius: 4, background: "#fff", outline: "none", boxSizing: "border-box" }} />
              <Button variant="filled" as="button">Submit</Button>
            </React.Fragment>
          )}
        </form>
      </div>
    </section>
  );
}

function Homepage({ onNav }) {
  return (
    <div>
      <Hero onNav={onNav} />
      <Insights />
      <About onNav={onNav} />
      <Services />
      <Partners />
      <Highlights />
      <Team onNav={onNav} />
      <ContactBar />
    </div>
  );
}
window.Homepage = Homepage;
